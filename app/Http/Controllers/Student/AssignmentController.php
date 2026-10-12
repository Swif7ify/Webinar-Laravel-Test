<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Services\StudentAssignmentService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AssignmentController extends Controller
{
    public function __construct(
        private StudentAssignmentService $service,
        private \App\Http\Services\SubmissionService $submissionService,
    ) {}

    public function index(): View
    {
        $studentId = Auth::id();
        $allTasks = $this->service->getTasksForStudent($studentId);

        $now = now();
        $endOfWeek = now()->endOfWeek();

        // Calculate metrics
        $metrics = [
            'totalActive' => $allTasks->filter(fn($t) => $t->status === 'active' && !$t->due_date->isPast())->count(),
            'dueThisWeek' => $allTasks->filter(fn($t) => $t->due_date->between($now, $endOfWeek) && $t->status === 'active')->count(),
            'overdue'     => $allTasks->filter(fn($t) => $t->due_date->isPast() && $t->status !== 'completed')->count(),
            'completed'   => $allTasks->filter(fn($t) => $t->status === 'completed')->count(),
        ];

        // Get paginated tasks
        $paginatedTasks = $this->service->getPaginatedTasksForStudent($studentId, 10);
        
        // Transform the items in the paginator
        $paginatedTasks->getCollection()->transform(fn($task) => $this->service->formatTaskForList($task));

        return view('pages.student.my-assignments', [
            'tasks'   => $paginatedTasks,
            'metrics' => $metrics,
        ]);
    }

    public function show(int $taskId): View
    {
        return $this->renderAssignmentDetails($taskId, route('student.assignments.index'));
    }

    public function showTaskOnClass(int $classId, int $taskId): View
    {
        return $this->renderAssignmentDetails($taskId, route('student.classes.show', $classId));
    }

    private function renderAssignmentDetails(int $taskId, string $backUrl): View
    {
        $task = $this->service->getTaskDetailsById(Auth::id(), $taskId);

        if (!$task) {
            abort(404, 'Assignment not found or you do not have permission to view it.');
        }

        $isOverdue = $task->due_date->isPast() && $task->status !== 'completed';
        $submission = $this->submissionService->getSubmission($taskId, Auth::id());
        $isSubmittedLate = $submission && $submission->submitted_at->greaterThan($task->due_date);

        $statusBadgeVariant = $task->status === 'completed' ? ($isSubmittedLate ? 'danger' : 'success') : ($isOverdue ? 'danger' : 'info');
        $statusBadgeLabel = $task->status === 'completed' ? ($isSubmittedLate ? 'Completed Late' : 'Completed') : ($isOverdue ? 'Overdue' : 'Active');

        $instructionsText = $task->instructions;

        if ($instructionsText === '') {
            $instructionsText = 'No additional instructions provided.';
        }

        $acceptedFormatsText = is_array($task->accepted_formats) && count($task->accepted_formats) > 0
            ? implode(', ', $task->accepted_formats)
            : 'Any';

        $submission = $this->submissionService->getSubmission($taskId, Auth::id());

        return view('pages.student.assignment-details', [
            'task' => $task,
            'submission' => $submission,
            'statusBadgeVariant' => $statusBadgeVariant,
            'statusBadgeLabel' => $statusBadgeLabel,
            'instructionsText' => $instructionsText,
            'acceptedFormatsText' => $acceptedFormatsText,
            'backUrl' => $backUrl,
        ]);
    }
}
