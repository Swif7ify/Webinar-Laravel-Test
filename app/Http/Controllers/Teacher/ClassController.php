<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\AddStudentsRequest;
use App\Http\Requests\Teacher\CreateClassRequest;
use App\Http\Requests\Teacher\StoreTaskRequest;
use App\Http\Requests\Teacher\UpdateTaskRequest;
use App\Http\Services\ClassService;
use App\Http\Services\TaskService;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;

class ClassController extends Controller
{
    public function __construct(
        private ClassService $service,
        private TaskService $taskService,
    ) {}

    public function index()
    {
        $teacherId       = Auth::id();
        $classes         = $this->service->getTeacherClasses($teacherId);
        $subjects        = Subject::orderBy('name')->get();
        $classCount      = $this->service->getTeacherClassCount($teacherId);
        $generatedCode   = $this->service->generateCode();
        $takenSubjectIds = $this->service->getTakenSubjectIds($teacherId);
        $takenSchedules  = $this->service->getTakenSchedules($teacherId);

        return view('pages.teacher.classes', compact(
            'classes', 'subjects', 'classCount', 'generatedCode', 'takenSubjectIds', 'takenSchedules'
        ));
    }

    public function store(CreateClassRequest $request)
    {
        try {
            $this->service->createClass(Auth::id(), $request->validated());
            return redirect()->route('teacher.classes.index')
                ->with('success', 'Class created successfully.');
        } catch (ValidationException $e) {
            $errors  = $e->errors();
            $message = $errors['limit'][0] ?? $errors['subject_id'][0] ?? $errors['schedule'][0] ?? 'Something went wrong.';
            return redirect()->route('teacher.classes.index')
                ->with('error', $message);
        }
    }

    public function show(int $classId)
    {
        $teacherId        = Auth::id();
        $schoolClass      = $this->service->getClassDetail($classId, $teacherId);
        $enrolledStudents = $this->service->getPaginatedEnrolledStudents($classId, 10);
        $modalStudents    = $this->service->getStudentsForModal($classId);
        $tasks            = $this->taskService->getPaginatedTasksForClass($classId, 10);

        return view('pages.teacher.class-show', compact('schoolClass', 'enrolledStudents', 'modalStudents', 'tasks'));
    }

    public function addStudents(AddStudentsRequest $request, int $classId)
    {
        $this->service->addStudentsToClass($classId, Auth::id(), $request->validated()['student_ids']);

        $newlyEnrolled = $this->service->getEnrolledStudents($classId)
            ->whereIn('id', $request->validated()['student_ids'])
            ->values();

        return response()->json([
            'students' => $newlyEnrolled->map(fn($s) => [
                'id'          => $s->id,
                'name'        => $s->name,
                'email'       => $s->email,
                'enrolled_at' => $s->enrolled_at,
            ]),
        ]);
    }

    public function storeTask(StoreTaskRequest $request, int $classId)
    {
        Log::info('StoreTask called', [
            'classId' => $classId,
            'files_count' => count($request->file('reference_files') ?? []),
            'files' => $request->file('reference_files'),
        ]);
        try {
            $this->taskService->createTask(
                $classId,
                Auth::id(),
                $request->validated(),
                $request->file('reference_files') ?? [],
            );
            return redirect()->route('teacher.classes.show', $classId)
                ->with('success', 'Task created successfully.');
        } catch (ValidationException $e) {
            $errors  = $e->errors();
            $message = $errors['reference_files'][0] ?? 'Failed to create task.';
            return redirect()->route('teacher.classes.show', $classId)
                ->with('error', $message);
        }
    }

    public function updateTask(UpdateTaskRequest $request, int $classId, int $taskId)
    {
        try {
            $this->taskService->updateTask(
                $classId,
                Auth::id(),
                $taskId,
                $request->validated(),
                $request->file('reference_files') ?? [],
            );
            return redirect()->route('teacher.classes.show', $classId)
                ->with('success', 'Task updated successfully.');
        } catch (ValidationException $e) {
            $errors  = $e->errors();
            $message = $errors['reference_files'][0] ?? 'Failed to update task.';
            return redirect()->route('teacher.classes.show', $classId)
                ->with('error', $message);
        }
    }

    public function destroyTask(int $classId, int $taskId)
    {
        $this->taskService->deleteTask($classId, Auth::id(), $taskId);

        return redirect()->route('teacher.classes.show', $classId)
            ->with('success', 'Task deleted successfully.');
    }

    public function archive(int $classId)
    {
        $this->service->archiveClass($classId, Auth::id());

        return Redirect::route('teacher.classes.index')
            ->with('success', 'Class archived successfully.');
    }
}
