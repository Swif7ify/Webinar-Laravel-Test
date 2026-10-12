<?php

namespace App\Http\Services;

use App\Http\Repositories\ClassRepository;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

class StudentAssignmentService
{
    public function __construct(
        private ClassRepository $repository,
        private \App\Http\Repositories\TaskRepository $taskRepository,
    ) {}

    public function getEnrolledClassesForStudent(int $studentId): Collection
    {
        return $this->repository->getEnrolledClassesForStudent($studentId);
    }

    public function getTasksForStudent(int $studentId): Collection
    {
        return $this->taskRepository->getForStudent($studentId);
    }

    public function getTaskDetailsById(int $studentId, int $taskId): ?Task
    {
        return $this->taskRepository->getTaskDetailsById($studentId, $taskId);
    }

    public function getPaginatedTasksForStudent(int $studentId, int $perPage = 5, ?string $status = null): \Illuminate\Pagination\LengthAwarePaginator
    {
        return $this->taskRepository->getPaginatedForStudent($studentId, $perPage, $status);
    }

    public function getClassDetailsForStudent(int $classId, int $studentId): ?array
    {
        $schoolClass = $this->repository->findByIdForStudent($classId, $studentId);

        if (!$schoolClass) {
            return null;
        }

        $paginatedTasks = $this->taskRepository->getPaginatedByClassForStudent($classId, $studentId, 10);
        $paginatedTasks->getCollection()->transform(fn($task) => $this->formatTaskForList($task));

        return [
            'schoolClass' => $schoolClass,
            'tasks'       => $paginatedTasks,
        ];
    }

    /**
     * Format a Task model for the main assignments list/page view.
     */
    public function formatTaskForList(Task $task): Task
    {
        $today = now()->startOfDay();
        $dueDay = $task->due_date->copy()->startOfDay();

        $isCompleted = $task->status === 'completed';
        $isOverdue = !$isCompleted && $task->due_date->isPast();

        $statusClass = 'active';
        $pillClass = 'upcoming';
        $pillLabel = 'Upcoming';

        if ($isCompleted) {
            $submission = $task->submissions->first();
            $isSubmittedLate = $submission && $submission->submitted_at->greaterThan($task->due_date);
            $isChecked = $submission && $submission->status === 'checked';

            $statusClass = 'completed';
            $pillClass = $isChecked ? 'success' : ($isSubmittedLate ? 'overdue' : 'completed');
            $pillLabel = $isChecked ? 'Checked' : ($isSubmittedLate ? 'Completed Late' : 'Completed');
        } elseif ($isOverdue) {
            $daysOverdue = $dueDay->diffInDays($today);
            $statusClass = 'overdue';
            $pillClass = 'overdue';
            $pillLabel = 'Overdue by ' . $daysOverdue . ' ' . \Illuminate\Support\Str::plural('day', $daysOverdue);
        } else {
            $daysUntil = $today->diffInDays($dueDay);

            if ($daysUntil <= 2) {
                $pillClass = 'urgent';
                $pillLabel = $daysUntil === 0
                    ? 'Due today'
                    : 'Due in ' . $daysUntil . ' ' . \Illuminate\Support\Str::plural('day', $daysUntil);
            }
        }

        $descriptionText = trim(preg_replace('/\s+/', ' ', strip_tags((string) $task->instructions)));

        if ($descriptionText === '') {
            $descriptionText = 'No additional instructions provided.';
        }

        $attachmentCount = $task->referenceFiles->count();

        $task->statusClass = $statusClass;
        $task->pillClass = $pillClass;
        $task->pillLabel = $pillLabel;
        $task->courseLabel = trim(($task->schoolClass->code ?? '') . ' • ' . ($task->schoolClass->subject->name ?? ''));
        $task->descriptionText = \Illuminate\Support\Str::limit($descriptionText, 140);
        $task->attachmentLabel = $attachmentCount . ' ' . \Illuminate\Support\Str::plural('Attachment', $attachmentCount);

        return $task;
    }

    /**
     * Get data for the student dashboard.
     */
    public function getDashboardData(int $studentId): array
    {
        // 1. Get due soon count (targeted query)
        $dueCount = $this->taskRepository->getDueSoonCount($studentId);

        // 2. Get active and overdue tasks for the widget (limited query)
        $activeAndOverdueTasks = $this->taskRepository->getActiveAndOverdueTasks($studentId, 5);
        $assignmentsForDashboard = $activeAndOverdueTasks
            ->map(fn($task) => $this->formatTaskForDashboard($task))
            ->values()
            ->toArray();

        // 3. Get upcoming deadlines (limited query)
        $upcomingDeadlines = $this->taskRepository->getUpcomingDeadlines($studentId, 3);
        $deadlines = $upcomingDeadlines->map(fn($task) => [
            'date'       => $task->due_date->isToday() ? 'Today' : ($task->due_date->isTomorrow() ? 'Tomorrow' : $task->due_date->format('M d')),
            'time'       => $task->due_date->format('g:i A'),
            'courseCode' => $task->schoolClass->code ?? 'N/A',
            'title'      => $task->title,
            'urgency'    => $this->getUrgencyLevel($task->due_date, $task->status === 'completed'),
        ])
        ->values()
        ->toArray();

        return [
            'dueCount'    => $dueCount,
            'assignments' => $assignmentsForDashboard,
            'deadlines'   => $deadlines,
        ];
    }

    /**
     * Format a Task model for the dashboard active assignments list.
     */
    private function formatTaskForDashboard(Task $task): array
    {
        $isOverdue = $task->due_date->isPast() && $task->status !== 'completed';
        $isCompleted = $task->status === 'completed';

        $clockIcon = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/></svg>';
        $warningIcon = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>';
        $checkIcon = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>';
        
        $assignmentIcon = $this->getTaskIcon($task);

        $status = 'Not Started';
        $statusVariant = 'neutral';
        $dueText = 'Due in ' . $task->due_date->diffForHumans(['parts' => 1]);
        $dueVariant = 'muted';
        $dueIcon = $clockIcon;
        $action = 'upload';

        if ($isCompleted) {
            $submission = $task->submissions->first();
            $isSubmittedLate = $submission && $submission->submitted_at->greaterThan($task->due_date);
            $isChecked = $submission && $submission->status === 'checked';

            $status = $isChecked ? 'Checked' : ($isSubmittedLate ? 'Late Submission' : 'Submitted');
            $statusVariant = $isChecked ? 'success' : ($isSubmittedLate ? 'danger' : 'success');
            $dueText = $isChecked ? 'Reviewed' : 'Completed';
            $dueVariant = 'muted';
            $dueIcon = $checkIcon;
            $action = 'view_submission';
        } elseif ($isOverdue) {
            $status = 'Late';
            $statusVariant = 'danger';
            $daysOverdue = now()->startOfDay()->diffInDays($task->due_date->copy()->startOfDay());
            $dueText = 'Overdue by ' . $daysOverdue . ' ' . \Illuminate\Support\Str::plural('day', $daysOverdue);
            $dueVariant = 'warning';
            $dueIcon = $warningIcon;
            $action = 'upload_now';
        } else {
            $daysUntil = now()->startOfDay()->diffInDays($task->due_date->copy()->startOfDay());
            if ($daysUntil <= 2) {
                $dueVariant = 'danger';
            }
        }

        return [
            'icon'           => $assignmentIcon['svg'],
            'iconColor'      => $assignmentIcon['color'],
            'title'          => $task->title,
            'courseCode'     => $task->schoolClass->code ?? 'N/A',
            'professor'      => $task->teacher->name ?? 'Unknown',
            'status'         => $status,
            'statusVariant'  => $statusVariant,
            'dueText'        => $dueText,
            'dueVariant'     => $dueVariant,
            'dueIcon'        => $dueIcon,
            'showViewDetails'=> true,
            'detailsHref'    => route('student.assignments.show', $task->id),
        ];
    }

    /**
     * Get an appropriate icon for a task based on its subject or title.
     */
    private function getTaskIcon(Task $task): array
    {
        $codeIcon = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5"/></svg>';
        $penIcon = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/></svg>';
        $calcIcon = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75V18m-7.5-6.75h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25v-.008zm2.498-4.5h.007v.008h-.007v-.008zm0 2.25h.007v.008h-.007v-.008zm0 2.25h.007v.008h-.007v-.008zm2.504-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008v-.008zm-7.498-6.75h.008v.008H5.25v-.008zm0 2.25h.008v.008H5.25v-.008zm0 2.25h.008v.008H5.25v-.008zm2.498-4.5h.007v.008h-.007v-.008zm0 2.25h.007v.008h-.007v-.008zm0 2.25h.007v.008h-.007v-.008zm2.504-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008v-.008zM8.25 6h7.5v2.25h-7.5V6zM12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25z"/></svg>';

        $subjectName = strtolower($task->schoolClass->subject->name ?? '');
        $title = strtolower($task->title);

        if (str_contains($subjectName, 'computer') || str_contains($subjectName, 'code') || str_contains($subjectName, 'programming') || str_contains($title, 'project')) {
            return ['svg' => $codeIcon, 'color' => 'warning'];
        }

        if (str_contains($subjectName, 'math') || str_contains($subjectName, 'calc') || str_contains($subjectName, 'phys') || str_contains($title, 'problem')) {
            return ['svg' => $calcIcon, 'color' => 'success'];
        }

        return ['svg' => $penIcon, 'color' => 'info'];
    }

    /**
     * Determine urgency level based on due date and completion status.
     */
    private function getUrgencyLevel(\Illuminate\Support\Carbon $dueDate, bool $isCompleted = false): string
    {
        if ($isCompleted) {
            return 'success'; // Green
        }

        $daysUntil = now()->startOfDay()->diffInDays($dueDate->copy()->startOfDay(), false);

        if ($daysUntil < 0) {
            return 'danger'; // Red
        }

        return 'normal'; // Neutral/Default for active
    }
}
