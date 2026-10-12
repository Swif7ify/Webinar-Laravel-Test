<?php

namespace App\Http\Services;

use App\Http\Repositories\ClassRepository;
use App\Http\Repositories\UserRepository;
use App\Models\SchoolClass;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Validation\ValidationException;

class ClassService
{
    private const CLASS_LIMIT = 3;

    public function __construct(
        private ClassRepository $repository,
        private UserRepository $userRepository,
    ) {}

    public function getTeacherClasses(int $teacherId): Collection
    {
        return $this->repository->getByTeacher($teacherId);
    }

    public function getTeacherClassCount(int $teacherId): int
    {
        return $this->repository->countByTeacher($teacherId);
    }

    public function getTakenSubjectIds(int $teacherId): SupportCollection
    {
        return $this->repository->getTakenSubjectIds($teacherId);
    }

    public function getTakenSchedules(int $teacherId): SupportCollection
    {
        return $this->repository->getTakenSchedules($teacherId);
    }

    public function createClass(int $teacherId, array $validated): SchoolClass
    {
        if ($this->repository->countByTeacher($teacherId) >= self::CLASS_LIMIT) {
            throw ValidationException::withMessages([
                'limit' => ['You have reached the maximum of ' . self::CLASS_LIMIT . ' classes.'],
            ]);
        }

        if ($this->repository->subjectExistsForTeacher($teacherId, (int) $validated['subject_id'])) {
            throw ValidationException::withMessages([
                'subject_id' => ['You already have a class for this subject.'],
            ]);
        }

        // Format times to "09:00 AM - 10:30 AM"
        $startTime = date('h:i A', strtotime($validated['start_time']));
        $endTime   = date('h:i A', strtotime($validated['end_time']));
        $scheduleTime = $startTime . ' - ' . $endTime;

        if ($this->repository->scheduleExistsForTeacher($teacherId, $validated['schedule_day'], $scheduleTime)) {
            throw ValidationException::withMessages([
                'schedule' => ['You already have a class scheduled on ' . $validated['schedule_day'] . ' at ' . $scheduleTime . '.'],
            ]);
        }

        return $this->repository->create([
            'code'          => $this->repository->generateUniqueCode(),
            'teacher_id'    => $teacherId,
            'subject_id'    => $validated['subject_id'],
            'schedule_day'  => $validated['schedule_day'],
            'schedule_time' => $scheduleTime,
        ]);
    }

    public function getClassDetail(int $classId, int $teacherId): SchoolClass
    {
        $schoolClass = $this->repository->findByIdForTeacher($classId, $teacherId);

        if (!$schoolClass) {
            abort(404);
        }

        return $schoolClass;
    }

    public function generateCode(): string
    {
        return $this->repository->generateUniqueCode();
    }

    public function getEnrolledStudents(int $classId): Collection
    {
        return $this->repository->getEnrolledStudents($classId);
    }

    public function getPaginatedEnrolledStudents(int $classId, int $perPage = 10): \Illuminate\Pagination\LengthAwarePaginator
    {
        return $this->repository->getPaginatedEnrolledStudents($classId, $perPage);
    }

    public function getStudentsForModal(int $classId): SupportCollection
    {
        $enrolledIds = $this->repository->getEnrolledStudents($classId)->pluck('id')->flip();
        $allStudents = $this->userRepository->getStudents();

        return $allStudents->map(fn($student) => [
            'id'         => $student->id,
            'name'       => $student->name,
            'email'      => $student->email,
            'enrolled'   => $enrolledIds->has($student->id),
        ]);
    }

    public function addStudentsToClass(int $classId, int $teacherId, array $studentIds): void
    {
        $schoolClass = $this->repository->findByIdForTeacher($classId, $teacherId);

        if (!$schoolClass) {
            abort(403);
        }

        $this->repository->addStudents($classId, $studentIds);
    }

    public function archiveClass(int $classId, int $teacherId): void
    {
        $schoolClass = $this->repository->findByIdForTeacher($classId, $teacherId);

        if (!$schoolClass) {
            abort(403);
        }

        $this->repository->archive($schoolClass);
    }
}
