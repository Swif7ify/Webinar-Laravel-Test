<?php

namespace App\Http\Repositories;

use App\Models\SchoolClass;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

class ClassRepository
{
    public function getByTeacher(int $teacherId): Collection
    {
        return SchoolClass::with('subject')
            ->where('teacher_id', $teacherId)
            ->where('status', 'active')
            ->latest()
            ->get();
    }

    public function countByTeacher(int $teacherId): int
    {
        return SchoolClass::where('teacher_id', $teacherId)
            ->where('status', 'active')
            ->count();
    }

    public function getTakenSubjectIds(int $teacherId): SupportCollection
    {
        return SchoolClass::where('teacher_id', $teacherId)
            ->where('status', 'active')
            ->pluck('subject_id');
    }

    public function getTakenSchedules(int $teacherId): SupportCollection
    {
        return SchoolClass::where('teacher_id', $teacherId)
            ->where('status', 'active')
            ->get(['schedule_day', 'schedule_time'])
            ->map(fn($c) => ['day' => $c->schedule_day, 'time' => $c->schedule_time]);
    }

    public function subjectExistsForTeacher(int $teacherId, int $subjectId): bool
    {
        return SchoolClass::where('teacher_id', $teacherId)
            ->where('status', 'active')
            ->where('subject_id', $subjectId)
            ->exists();
    }

    public function scheduleExistsForTeacher(int $teacherId, string $day, string $time): bool
    {
        return SchoolClass::where('teacher_id', $teacherId)
            ->where('status', 'active')
            ->where('schedule_day', $day)
            ->where('schedule_time', $time)
            ->exists();
    }

    public function findByIdForTeacher(int $classId, int $teacherId): ?SchoolClass
    {
        return SchoolClass::with('subject')
            ->where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->where('status', 'active')
            ->first();
    }

    public function generateUniqueCode(): string
    {
        do {
            $code = str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        } while (SchoolClass::where('code', $code)->exists());

        return $code;
    }

    public function create(array $data): SchoolClass
    {
        return SchoolClass::create($data);
    }

    public function getEnrolledStudents(int $classId): Collection
    {
        return SchoolClass::findOrFail($classId)->students()->orderBy('name')->get(['users.id', 'users.name', 'users.email', 'class_student.created_at as enrolled_at']);
    }

    public function getPaginatedEnrolledStudents(int $classId, int $perPage = 10): \Illuminate\Pagination\LengthAwarePaginator
    {
        return SchoolClass::findOrFail($classId)
            ->students()
            ->orderBy('name')
            ->paginate($perPage, ['users.id', 'users.name', 'users.email', 'class_student.created_at as enrolled_at'], 'students_page');
    }

    public function addStudents(int $classId, array $studentIds): void
    {
        SchoolClass::findOrFail($classId)->students()->syncWithoutDetaching($studentIds);
    }

    public function getEnrolledClassesForStudent(int $studentId): Collection
    {
        return SchoolClass::with(['subject', 'teacher'])
            ->whereHas('students', fn($q) => $q->where('student_id', $studentId))
            ->where('status', 'active')
            ->latest()
            ->get();
    }

    public function findByIdForStudent(int $classId, int $studentId): ?SchoolClass
    {
        return SchoolClass::with([
                'subject:id,name', 
                'teacher:id,name', 
                'tasks' => function($q) {
                    $q->select('id', 'class_id', 'title', 'instructions', 'due_date', 'status');
                },
                'tasks.referenceFiles' => function($q) {
                    $q->select('id', 'task_id', 'original_name', 'stored_path');
                }
            ])
            ->where('id', $classId)
            ->whereHas('students', fn($q) => $q->where('student_id', $studentId))
            ->where('status', 'active')
            ->first();
    }

    public function archive(SchoolClass $schoolClass): void
    {
        $schoolClass->update([
            'status' => 'archived',
            'archived_at' => now(),
        ]);
    }
}
