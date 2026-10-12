<?php

namespace App\Http\Repositories;

use App\Models\Submission;

class SubmissionRepository
{
    public function create(array $data): Submission
    {
        return Submission::create($data);
    }

    public function findByTaskAndStudent(int $taskId, int $studentId): ?Submission
    {
        return Submission::with('files')
            ->where('task_id', $taskId)
            ->where('student_id', $studentId)
            ->first();
    }

    public function getSubmissionsForTask(int $taskId): \Illuminate\Database\Eloquent\Collection
    {
        return Submission::with(['student', 'files'])
            ->where('task_id', $taskId)
            ->orderBy('submitted_at', 'desc')
            ->get();
    }

    public function getPaginatedSubmissionsForTask(int $taskId, int $perPage = 10): \Illuminate\Pagination\LengthAwarePaginator
    {
        return Submission::with(['student', 'files'])
            ->where('task_id', $taskId)
            ->orderBy('submitted_at', 'desc')
            ->paginate($perPage, ['*'], 'submissions_page');
    }

    public function findById(int $id): ?Submission
    {
        return Submission::with(['student', 'task', 'files'])->find($id);
    }

    public function markAsChecked(int $id): bool
    {
        $submission = Submission::find($id);
        if (!$submission) return false;

        return $submission->update([
            'status'    => 'checked',
            'graded_at' => now(),
        ]);
    }

    public function createFile(array $data): \App\Models\SubmissionFile
    {
        return \App\Models\SubmissionFile::create($data);
    }

    public function delete(Submission $submission): bool
    {
        return $submission->delete();
    }

    public function findFileById(int $id): ?\App\Models\SubmissionFile
    {
        return \App\Models\SubmissionFile::with('submission.task')->find($id);
    }

    public function getHistoryForTeacher(int $teacherId, array $filters = []): \Illuminate\Pagination\LengthAwarePaginator
    {
        $query = Submission::query()
            ->select('submissions.*')
            ->join('tasks', 'submissions.task_id', '=', 'tasks.id')
            ->with(['student', 'task.schoolClass.subject'])
            ->where('tasks.teacher_id', $teacherId);

        if (!empty($filters['student_id'])) {
            $query->where('submissions.student_id', $filters['student_id']);
        }

        if (!empty($filters['subject_id'])) {
            $query->whereHas('task.schoolClass', function ($q) use ($filters) {
                $q->where('subject_id', $filters['subject_id']);
            });
        }

        if (!empty($filters['status'])) {
            if ($filters['status'] === 'late') {
                $query->whereColumn('submissions.submitted_at', '>', 'tasks.due_date');
            } elseif ($filters['status'] === 'ontime') {
                $query->whereColumn('submissions.submitted_at', '<=', 'tasks.due_date');
            }
        }

        if (!empty($filters['grading'])) {
            $query->where('submissions.status', $filters['grading']);
        }

        return $query->orderBy('submissions.submitted_at', 'desc')->paginate(15);
    }
}
