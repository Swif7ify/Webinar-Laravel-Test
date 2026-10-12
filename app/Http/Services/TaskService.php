<?php

namespace App\Http\Services;

use App\Http\Repositories\ClassRepository;
use App\Http\Repositories\TaskRepository;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class TaskService
{
    public function __construct(
        private TaskRepository $repository,
        private ClassRepository $classRepository,
    ) {}

    public function createTask(int $classId, int $teacherId, array $validated, array $files): Task
    {
        $schoolClass = $this->classRepository->findByIdForTeacher($classId, $teacherId);

        if (!$schoolClass) {
            abort(403);
        }

        $dueDate = $validated['due_date'] . ' ' . $validated['due_time'] . ':00';

        $task = $this->repository->create([
            'class_id'          => $classId,
            'teacher_id'        => $teacherId,
            'title'             => $validated['title'],
            'instructions'      => $validated['instructions'] ?? null,
            'due_date'          => $dueDate,
            'accepted_formats'  => $validated['accepted_formats'],
            'max_file_size_mb'  => $validated['max_file_size_mb'],
            'status'            => 'active',
        ]);

        foreach ($files as $file) {
            $this->storeReferenceFile($task->id, $file);
        }

        return $task;
    }

    public function updateTask(int $classId, int $teacherId, int $taskId, array $validated, array $files): void
    {
        $schoolClass = $this->classRepository->findByIdForTeacher($classId, $teacherId);

        if (!$schoolClass) {
            abort(403);
        }

        $task = $this->repository->findByIdForTeacher($taskId, $teacherId);

        if (!$task) {
            abort(404);
        }

        $dueDate = $validated['due_date'] . ' ' . $validated['due_time'] . ':00';

        $this->repository->updateById($taskId, [
            'title'            => $validated['title'],
            'instructions'     => $validated['instructions'] ?? null,
            'due_date'         => $dueDate,
            'accepted_formats' => $validated['accepted_formats'],
            'max_file_size_mb' => $validated['max_file_size_mb'],
        ]);

        if (!empty($files)) {
            $this->deleteReferenceFilesFromDisk($taskId);
            $this->repository->deleteReferenceFilesByTaskId($taskId);
            foreach ($files as $file) {
                $this->storeReferenceFile($taskId, $file);
            }
        }
    }

    public function deleteTask(int $classId, int $teacherId, int $taskId): void
    {
        $schoolClass = $this->classRepository->findByIdForTeacher($classId, $teacherId);

        if (!$schoolClass) {
            abort(403);
        }

        $task = $this->repository->findByIdForTeacher($taskId, $teacherId);

        if (!$task) {
            abort(404);
        }

        $this->deleteReferenceFilesFromDisk($taskId);
        $this->repository->deleteTaskById($taskId);

        Storage::disk('local')->deleteDirectory("tasks/{$taskId}");
        if (Storage::disk('public')->exists("tasks/{$taskId}")) {
            Storage::disk('public')->deleteDirectory("tasks/{$taskId}");
        }
    }

    public function getTasksForClass(int $classId): Collection
    {
        return $this->repository->getByClass($classId);
    }

    public function getPaginatedTasksForClass(int $classId, int $perPage = 10): \Illuminate\Pagination\LengthAwarePaginator
    {
        return $this->repository->getPaginatedByClass($classId, $perPage);
    }

    public function getTaskById(int $taskId): ?Task
    {
        return $this->repository->findById($taskId);
    }

    private function deleteReferenceFilesFromDisk(int $taskId): void
    {
        $files = $this->repository->getReferenceFilesByTaskId($taskId);
        foreach ($files as $file) {
            Storage::disk('local')->delete($file->stored_path);
            if (Storage::disk('public')->exists($file->stored_path)) {
                Storage::disk('public')->delete($file->stored_path);
            }
        }
    }

    private function storeReferenceFile(int $taskId, UploadedFile $file): void
    {
        $originalName = $file->getClientOriginalName();

        if ($this->repository->referenceFileNameExistsForTask($taskId, $originalName)) {
            throw ValidationException::withMessages([
                'reference_files' => ["A file named \"{$originalName}\" has already been uploaded for this task."],
            ]);
        }

        $storedPath = Storage::disk('local')->putFileAs(
            "tasks/{$taskId}/references",
            $file,
            $originalName,
        );

        $this->repository->createReferenceFile($taskId, [
            'original_name' => $originalName,
            'stored_path'   => $storedPath,
            'size_bytes'    => $file->getSize(),
        ]);
    }

    public function getTasksForTeacherCalendar(int $teacherId): Collection
    {
        return $this->repository->getTasksByTeacher($teacherId);
    }
}
