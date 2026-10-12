<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Services\StudentAssignmentService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ClassController extends Controller
{
    public function __construct(
        private StudentAssignmentService $service,
    ) {}

    public function index(): View
    {
        $classes = $this->service->getEnrolledClassesForStudent(Auth::id());

        return view('pages.student.classes', [
            'classes' => $classes,
            'classCount' => $classes->count(),
        ]);
    }

    public function show(int $classId): View
    {
        $data = $this->service->getClassDetailsForStudent($classId, Auth::id());

        if (!$data) {
            abort(404, 'Class not found or you are not enrolled.');
        }

        return view('pages.student.class-details', $data);
    }
}