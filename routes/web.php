<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\ClassController as StudentClassController;
use App\Http\Controllers\Student\AssignmentController as StudentAssignmentController;
use App\Http\Controllers\Student\SubmissionController as StudentSubmissionController;
use App\Http\Controllers\Student\CalendarController as StudentCalendarController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\ClassController as TeacherClassController;
use App\Http\Controllers\Teacher\SubmissionController as TeacherSubmissionController;
use App\Http\Controllers\Teacher\CalendarController as TeacherCalendarController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    if (auth()->user()->role === 'teacher') {
        return redirect()->route('teacher.dashboard');
    }
    return redirect()->route('student.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::prefix('student')->name('student.')->middleware('role:student')->group(function () {
        Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
        
        Route::controller(StudentClassController::class)->group(function () {
            Route::get('/classes', 'index')->name('classes.index');
            Route::get('/classes/{class}', 'show')->name('classes.show');
        });

        Route::controller(StudentAssignmentController::class)->group(function () {
            Route::get('/classes/{class}/tasks/{task}', 'showTaskOnClass')->name('classes.tasks.show');
            Route::get('/assignments', 'index')->name('assignments.index');
            Route::get('/assignments/{taskId}', 'show')->name('assignments.show');
        });

        Route::post('/assignments/{task}/submit', [StudentSubmissionController::class, 'store'])
            ->middleware('throttle:uploads')
            ->name('assignments.submit');
        Route::delete('/assignments/{task}/submission', [StudentSubmissionController::class, 'destroy'])
            ->middleware('throttle:submissions-mutate')
            ->name('assignments.submission.delete');
        Route::get('/calendar', [StudentCalendarController::class, 'index'])->name('calendar');
    });

    Route::prefix('teacher')->name('teacher.')->middleware('role:teacher')->group(function () {
        Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
        
        Route::controller(TeacherClassController::class)->group(function () {
            Route::get('/classes', 'index')->name('classes.index');
            Route::post('/classes', 'store')->name('classes.store');
            Route::get('/classes/{class}', 'show')->name('classes.show');
            Route::patch('/classes/{class}/archive', 'archive')->name('classes.archive');
            Route::post('/classes/{class}/students', 'addStudents')->name('classes.students.add');
            Route::post('/classes/{class}/tasks', 'storeTask')
                ->middleware('throttle:uploads')
                ->name('classes.tasks.store');
            Route::post('/classes/{class}/tasks/{task}', 'updateTask')
                ->middleware('throttle:uploads')
                ->name('classes.tasks.update');
            Route::delete('/classes/{class}/tasks/{task}', 'destroyTask')->name('classes.tasks.destroy');
        });

        Route::controller(TeacherSubmissionController::class)->group(function () {
            Route::get('/tasks/{task}/submissions', 'index')->name('tasks.submissions.index');
            Route::get('/submissions', 'history')->name('submissions.history');
            Route::get('/submissions/{submission}', 'show')->name('submissions.show');
            Route::post('/submissions/{submission}/mark-as-checked', 'markAsChecked')->name('submissions.mark-as-checked');
        });

        Route::get('/calendar', [TeacherCalendarController::class, 'index'])->name('calendar');
    });

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    Route::controller(FileController::class)->middleware('throttle:60,1')->group(function () {
        Route::get('/files/download/{fileId}', 'download')->name('files.download');
        Route::get('/files/reference/{fileId}', 'downloadReference')->name('files.reference.download');
    });
});

require __DIR__.'/auth.php';
