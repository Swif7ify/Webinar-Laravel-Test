<?php

namespace App\Http\Services;

use App\Http\Repositories\SubmissionRepository;
use App\Http\Repositories\TaskRepository;
use App\Models\Submission;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TeacherDashboardService
{
    public function __construct(
        private TaskRepository $taskRepository,
        private SubmissionRepository $submissionRepository
    ) {}

    public function getDashboardData(int $teacherId): array
    {
        // 1. Total Assignments (Tasks created by teacher)
        $totalAssignments = Task::where('teacher_id', $teacherId)->count();
        
        // 2. Total Submissions for tasks created by teacher
        $totalSubmissions = Submission::whereHas('task', function($q) use ($teacherId) {
            $q->where('teacher_id', $teacherId);
        })->count();

        // 3. Pending / Late
        // Pending review: submissions with status 'pending'
        $pendingReview = Submission::whereHas('task', function($q) use ($teacherId) {
            $q->where('teacher_id', $teacherId);
        })->where('status', 'pending')->count();

        // Late/Overdue: tasks that are past due date and not completed
        $overdueTasks = Task::where('teacher_id', $teacherId)
            ->where('due_date', '<', now())
            ->where('status', '!=', 'completed')
            ->count();
        
        $pendingLateCount = $pendingReview + $overdueTasks;

        // 4. Submission Preview (Latest 5)
        $submissionsPreview = Submission::with(['student', 'task.schoolClass.subject'])
            ->whereHas('task', function($q) use ($teacherId) {
                $q->where('teacher_id', $teacherId);
            })
            ->orderBy('submitted_at', 'desc')
            ->limit(5)
            ->get()
            ->map(fn($s) => [
                'id'            => $s->id,
                'studentName'   => $s->student->name,
                'subjectName'   => $s->task->schoolClass->subject->name,
                'taskTitle'     => $s->task->title,
                'submittedAt'   => $s->submitted_at->diffForHumans(),
                'status'        => $s->status,
                'isLate'        => $s->submitted_at->greaterThan($s->task->due_date),
            ]);

        return [
            'stats' => [
                'totalAssignments' => $totalAssignments,
                'totalSubmissions' => $totalSubmissions,
                'pendingLate'      => $pendingLateCount,
            ],
            'submissionsPreview' => $submissionsPreview,
        ];
    }
}
