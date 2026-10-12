<?php

namespace App\Http\Controllers;

use App\Http\Services\SubmissionService;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController extends Controller
{
    public function __construct(
        private SubmissionService $submissionService
    ) {}

    public function download(int $fileId): StreamedResponse
    {
        $file = $this->submissionService->getSubmissionFile($fileId);

        if (!$file) {
            abort(404);
        }

        $user = auth()->user();
        $submission = $file->submission;

        $isTeacher = $user->role === 'teacher' && $submission->task->teacher_id === $user->id;
        $isStudent = $user->role === 'student' && $submission->student_id === $user->id;

        if (!$isTeacher && !$isStudent) {
            abort(403);
        }

        $disk = Storage::disk('local')->exists($file->file_path)
            ? 'local'
            : (Storage::disk('public')->exists($file->file_path) ? 'public' : null);

        if (!$disk) {
            abort(404);
        }

        return Storage::disk($disk)->download(
            $file->file_path,
            $file->original_name
        );
    }

    public function downloadReference(int $fileId): StreamedResponse
    {
        $file = \App\Models\TaskReferenceFile::find($fileId);

        if (!$file) {
            abort(404);
        }

        $user = auth()->user();
        $task = $file->task;

        $isTeacher = $user->role === 'teacher' && $task->teacher_id === $user->id;
        $isStudent = $user->role === 'student' && $user->enrolledClasses()->where('class_id', $task->class_id)->exists();

        if (!$isTeacher && !$isStudent) {
            abort(403);
        }

        $disk = Storage::disk('local')->exists($file->stored_path)
            ? 'local'
            : (Storage::disk('public')->exists($file->stored_path) ? 'public' : null);

        if (!$disk) {
            abort(404);
        }

        return Storage::disk($disk)->download(
            $file->stored_path,
            $file->original_name
        );
    }
}
