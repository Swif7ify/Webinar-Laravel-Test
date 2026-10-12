<?php

namespace App\Http\Services;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Route;

class CalendarService
{
    /**
     * Format a collection of tasks for FullCalendar.
     */
    public function formatTasksForCalendar(Collection $tasks, string $userRole = 'student'): array
    {
        return $tasks->map(function (Task $task) use ($userRole) {
            $isOverdue = $task->due_date->isPast() && $task->status !== 'completed';
            $isCompleted = $task->status === 'completed';

            // Use consistent design system colors
            // These should match the CSS variables if possible, 
            // but FullCalendar 'color' property expects hex/css-color.
            $color = '#6E56CF'; // --color-primary
            
            if ($isCompleted) {
                $color = '#16A34A'; // --color-success
            } elseif ($isOverdue) {
                $color = '#DC2626'; // --color-error
            } elseif ($task->due_date->diffInDays(now()) <= 2) {
                $color = '#EA580C'; // --color-warning
            }

            $route = $userRole === 'teacher' 
                ? route('teacher.tasks.submissions.index', $task->id)
                : route('student.assignments.show', $task->id);

            return [
                'id'    => $task->id,
                'title' => $task->title,
                'start' => $task->due_date->toIso8601String(),
                'url'   => $route,
                'color' => $color,
                'extendedProps' => [
                    'course' => $task->schoolClass->subject->name ?? 'N/A',
                    'status' => $task->status,
                ]
            ];
        })->toArray();
    }
}
