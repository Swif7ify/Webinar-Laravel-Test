<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\GradeSubmissionRequest;
use App\Http\Services\SubmissionService;
use App\Http\Services\TaskService;
use App\Models\Submission;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubmissionController extends Controller
{
    public function __construct(
        private SubmissionService $service,
        private TaskService $taskService
    ) {}

    public function index(int $taskId)
    {
        $task = $this->taskService->getTaskById($taskId);
        
        // Ensure the teacher owns this task
        if ($task->teacher_id !== Auth::id()) {
            abort(403);
        }

        $submissions = $this->service->getPaginatedTaskSubmissions($taskId, 10);

        return view('pages.teacher.submissions.index', compact('task', 'submissions'));
    }

    public function show(int $submissionId)
    {
        $submission = $this->service->getSubmissionById($submissionId);

        if (!$submission || $submission->task->teacher_id !== Auth::id()) {
            abort(403);
        }

        return view('pages.teacher.submissions.show', compact('submission'));
    }

    public function markAsChecked(Request $request, int $submissionId)
    {
        $submission = $this->service->getSubmissionById($submissionId);

        if (!$submission || $submission->task->teacher_id !== Auth::id()) {
            abort(403);
        }

        $this->service->markAsChecked($submissionId);

        return redirect()->route('teacher.tasks.submissions.index', $submission->task_id)
            ->with('success', 'Submission marked as checked.');
    }

    public function history(Request $request)
    {
        $filters = $request->only(['student_id', 'subject_id', 'status', 'grading']);
        $data = $this->service->getSubmissionHistory(Auth::id(), $filters);

        return view('pages.teacher.submissions.history', array_merge($data, ['filters' => $filters]));
    }
}
