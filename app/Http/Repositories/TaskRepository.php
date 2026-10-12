<?php

namespace App\Http\Repositories;

use App\Models\Task;
use App\Models\TaskReferenceFile;
use Illuminate\Database\Eloquent\Collection;

class TaskRepository
{
    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function updateById(int $taskId, array $data): void
    {
        Task::where('id', $taskId)->update($data);
    }

    public function deleteReferenceFilesByTaskId(int $taskId): void
    {
        TaskReferenceFile::where('task_id', $taskId)->delete();
    }

    public function getReferenceFilesByTaskId(int $taskId): Collection
    {
        return TaskReferenceFile::where('task_id', $taskId)->get();
    }

    public function createReferenceFile(int $taskId, array $data): TaskReferenceFile
    {
        return TaskReferenceFile::create(array_merge(['task_id' => $taskId], $data));
    }

    public function findByIdForTeacher(int $taskId, int $teacherId): ?Task
    {
        return Task::with('referenceFiles')
            ->where('id', $taskId)
            ->where('teacher_id', $teacherId)
            ->first();
    }

    public function getTasksByTeacher(int $teacherId): Collection
    {
        return Task::with('schoolClass.subject')
            ->where('teacher_id', $teacherId)
            ->orderBy('due_date')
            ->get();
    }

    public function getByClass(int $classId): Collection
    {
        return Task::with('referenceFiles')
            ->where('class_id', $classId)
            ->orderBy('due_date')
            ->get();
    }

    public function getPaginatedByClass(int $classId, int $perPage = 10): \Illuminate\Pagination\LengthAwarePaginator
    {
        return Task::with('referenceFiles')
            ->where('class_id', $classId)
            ->orderBy('due_date', 'desc')
            ->paginate($perPage, ['*'], 'tasks_page');
    }

    public function referenceFileNameExistsForTask(int $taskId, string $originalName): bool
    {
        return TaskReferenceFile::where('task_id', $taskId)
            ->where('original_name', $originalName)
            ->exists();
    }

    public function getForStudent(int $studentId): Collection
    {
        return Task::with(['schoolClass.subject', 'teacher', 'referenceFiles', 'submissions' => function($query) use ($studentId) {
                $query->where('student_id', $studentId);
            }])
            ->whereIn('tasks.class_id', function ($query) use ($studentId) {
                $query->select('class_id')
                      ->from('class_student')
                      ->where('student_id', $studentId);
            })
            ->orderBy('due_date')
            ->get();
    }

    public function getTaskDetailsById(int $studentId, int $taskId): ?Task
    {
        return Task::with(['schoolClass.subject', 'teacher', 'referenceFiles', 'submissions' => function($query) use ($studentId) {
                $query->where('student_id', $studentId);
            }])
            ->where('id', $taskId)
            ->whereIn('tasks.class_id', function ($query) use ($studentId) {
                $query->select('class_id')
                      ->from('class_student')
                      ->where('student_id', $studentId);
            })
            ->first();
    }

    public function getPaginatedForStudent(int $studentId, int $perPage = 5, ?string $status = null): \Illuminate\Pagination\LengthAwarePaginator
    {
        $query = Task::with(['schoolClass.subject', 'teacher', 'submissions' => function($q) use ($studentId) {
                $q->where('student_id', $studentId);
            }])
            ->whereIn('tasks.class_id', function ($q) use ($studentId) {
                $q->select('class_id')
                  ->from('class_student')
                  ->where('student_id', $studentId);
            });

        if ($status === 'active') {
            $query->where('status', 'active')->where('due_date', '>=', now());
        } elseif ($status === 'completed') {
            $query->where('status', 'completed');
        } elseif ($status === 'overdue') {
            $query->where('status', '!=', 'completed')->where('due_date', '<', now());
        }

        return $query->orderBy('due_date')->paginate($perPage);
    }

    public function deleteTaskById(int $taskId): void
    {
        Task::where('id', $taskId)->delete();
    }

    public function findById(int $id): ?Task
    {
        return Task::with(['schoolClass.subject', 'teacher', 'referenceFiles'])->find($id);
    }

    public function getDueSoonCount(int $studentId): int
    {
        return Task::where('status', 'active')
            ->where('due_date', '>', now())
            ->where('due_date', '<=', now()->addDays(7))
            ->whereIn('tasks.class_id', function ($q) use ($studentId) {
                $q->select('class_id')
                  ->from('class_student')
                  ->where('student_id', $studentId);
            })
            ->count();
    }

    public function getActiveAndOverdueTasks(int $studentId, int $limit = 5): Collection
    {
        return Task::with(['schoolClass.subject', 'teacher', 'submissions' => function($q) use ($studentId) {
                $q->where('student_id', $studentId);
            }])
            ->whereIn('tasks.class_id', function ($q) use ($studentId) {
                $q->select('class_id')
                  ->from('class_student')
                  ->where('student_id', $studentId);
            })
            ->where(function($query) {
                $query->where(function($q) {
                    $q->where('status', 'active')->where('due_date', '>=', now());
                })->orWhere(function($q) {
                    $q->where('status', '!=', 'completed')->where('due_date', '<', now());
                });
            })
            ->orderBy('due_date', 'asc')
            ->limit($limit)
            ->get();
    }

    public function getUpcomingDeadlines(int $studentId, int $limit = 3): Collection
    {
        return Task::with(['schoolClass'])
            ->whereIn('tasks.class_id', function ($q) use ($studentId) {
                $q->select('class_id')
                  ->from('class_student')
                  ->where('student_id', $studentId);
            })
            ->orderByRaw("CASE 
                WHEN due_date < NOW() AND status != 'completed' THEN 0 
                WHEN status != 'completed' THEN 1 
                ELSE 2 
            END ASC")
            ->orderBy('due_date', 'asc')
            ->limit($limit)
            ->get();
    }

    public function getPaginatedByClassForStudent(int $classId, int $studentId, int $perPage = 10): \Illuminate\Pagination\LengthAwarePaginator
    {
        return Task::with(['schoolClass.subject', 'referenceFiles', 'submissions' => function($q) use ($studentId) {
                $q->where('student_id', $studentId);
            }])
            ->where('class_id', $classId)
            ->whereIn('tasks.class_id', function ($q) use ($studentId) {
                $q->select('class_id')
                  ->from('class_student')
                  ->where('student_id', $studentId);
            })
            ->orderBy('due_date', 'desc')
            ->paginate($perPage, ['*'], 'tasks_page');
    }
}
