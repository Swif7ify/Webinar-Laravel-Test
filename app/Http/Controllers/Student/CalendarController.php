<?php
/**
 * Controller for the Student Calendar.
 * Handles fetching tasks and presenting them in a Google Calendar-like view.
 */

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Services\StudentAssignmentService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CalendarController extends Controller
{
    public function __construct(
        private StudentAssignmentService $service,
        private \App\Http\Services\CalendarService $calendarService
    ) {}

    /**
     * Display the calendar view.
     */
    public function index(): View
    {
        $tasks = $this->service->getTasksForStudent(Auth::id());
        $events = $this->calendarService->formatTasksForCalendar($tasks, 'student');

        return view('pages.student.calendar', [
            'events' => $events
        ]);
    }
}
