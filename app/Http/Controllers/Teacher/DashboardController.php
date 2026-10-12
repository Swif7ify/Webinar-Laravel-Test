<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private \App\Http\Services\TeacherDashboardService $service
    ) {}

    /**
     * Display the teacher dashboard.
     */
    public function index(): View
    {
        $data = $this->service->getDashboardData(\Illuminate\Support\Facades\Auth::id());
        
        return view('pages.teacher.dashboard', [
            'stats'              => $data['stats'],
            'submissionsPreview' => $data['submissionsPreview'],
        ]);
    }
}
