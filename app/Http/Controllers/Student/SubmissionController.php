<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StoreSubmissionRequest;
use App\Http\Services\SubmissionService;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;

class SubmissionController extends Controller
{
    public function __construct(
        private SubmissionService $submissionService
    ) {}

    public function store(StoreSubmissionRequest $request, Task $task): RedirectResponse
    {
        try {
            $this->submissionService->submitTask(
                $task,
                auth()->id(),
                $request->file('files')
            );

            return back()->with('success', 'Assignment submitted successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['files' => $e->getMessage()]);
        }
    }

    public function destroy(Task $task): RedirectResponse
    {
        try {
            $this->submissionService->removeSubmission($task, auth()->id());

            return back()->with('success', 'Submission removed. You can upload a new file.');
        } catch (\Exception $e) {
            return back()->withErrors(['files' => $e->getMessage()]);
        }
    }
}
