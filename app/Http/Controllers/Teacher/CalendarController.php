<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Services\TaskService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CalendarController extends Controller
{
    public function __construct(
        private TaskService $service,
        private \App\Http\Services\CalendarService $calendarService
    ) {}

    public function index(): View
    {
        $tasks = $this->service->getTasksForTeacherCalendar(Auth::id());
        $events = $this->calendarService->formatTasksForCalendar($tasks, 'teacher');

        return view('pages.teacher.calendar', [
            'events' => $events
        ]);
    }
}
