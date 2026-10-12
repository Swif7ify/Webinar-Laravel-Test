<?php

namespace App\Http\Services;

use App\Http\Repositories\SubmissionRepository;
use App\Models\Submission;
use App\Models\Task;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SubmissionService
{
    public function __construct(
        private SubmissionRepository $repository
    ) {}

    public function submitTask(Task $task, int $studentId, array $files): Submission
    {
        // Check if a submission already exists
        $submission = $this->repository->findByTaskAndStudent($task->id, $studentId);
        
        if ($submission) {
            foreach ($submission->files as $oldFile) {
                Storage::disk('local')->delete($oldFile->file_path);
                if (Storage::disk('public')->exists($oldFile->file_path)) {
                    Storage::disk('public')->delete($oldFile->file_path);
                }
                $oldFile->delete();
            }

            $submission->submitted_at = now();
            $submission->save();
        } else {
            $submission = $this->repository->create([
                'task_id'        => $task->id,
                'student_id'     => $studentId,
                'submitted_at'   => now(),
            ]);
        }

        foreach ($files as $file) {
            $path = $file->store('submissions/' . $task->id, 'local');
            
            $this->repository->createFile([
                'submission_id' => $submission->id,
                'file_path'     => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime_type'     => $file->getMimeType(),
                'size_bytes'    => $file->getSize(),
            ]);
        }

        $task->status = 'completed';
        $task->save();

        return $submission->load('files');
    }

    public function removeSubmission(Task $task, int $studentId): void
    {
        $submission = $this->repository->findByTaskAndStudent($task->id, $studentId);

        if (!$submission) {
            throw new \RuntimeException('No submission found to remove.');
        }

        if ($submission->status === 'checked') {
            throw new \RuntimeException('Reviewed submissions cannot be removed.');
        }

        foreach ($submission->files as $oldFile) {
            Storage::disk('local')->delete($oldFile->file_path);
            if (Storage::disk('public')->exists($oldFile->file_path)) {
                Storage::disk('public')->delete($oldFile->file_path);
            }
            $oldFile->delete();
        }

        $this->repository->delete($submission);
    }

    public function getSubmission(int $taskId, int $studentId): ?Submission
    {
        return $this->repository->findByTaskAndStudent($taskId, $studentId);
    }

    public function getTaskSubmissions(int $taskId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->repository->getSubmissionsForTask($taskId);
    }

    public function getPaginatedTaskSubmissions(int $taskId, int $perPage = 10): \Illuminate\Pagination\LengthAwarePaginator
    {
        return $this->repository->getPaginatedSubmissionsForTask($taskId, $perPage);
    }

    public function getSubmissionById(int $id): ?Submission
    {
        return $this->repository->findById($id);
    }

    public function markAsChecked(int $id): bool
    {
        return $this->repository->markAsChecked($id);
    }

    public function getSubmissionFile(int $id): ?\App\Models\SubmissionFile
    {
        return $this->repository->findFileById($id);
    }

    public function getSubmissionHistory(int $teacherId, array $filters = []): array
    {
        $submissions = $this->repository->getHistoryForTeacher($teacherId, $filters);
        
        $students = \App\Models\User::where('role', 'student')
            ->whereHas('enrolledClasses', function($q) use ($teacherId) {
                $q->where('teacher_id', $teacherId);
            })
            ->orderBy('name')
            ->get(['id', 'name']);

        $subjects = \App\Models\Subject::whereHas('schoolClasses', function($q) use ($teacherId) {
                $q->where('teacher_id', $teacherId);
            })
            ->orderBy('name')
            ->get(['id', 'name']);

        return [
            'submissions' => $submissions,
            'students'    => $students,
            'subjects'    => $subjects,
        ];
    }
}
