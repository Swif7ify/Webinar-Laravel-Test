<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Services\StudentAssignmentService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        private StudentAssignmentService $service,
    ) {}

    /**
     * Display the student dashboard.
     */
    public function index(): View
    {
        $data = $this->service->getDashboardData(Auth::id());

        return view('pages.student.dashboard', $data);
    }
}