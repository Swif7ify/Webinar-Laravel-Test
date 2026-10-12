<?php

namespace Tests\Feature;

use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Submission;
use App\Models\SubmissionFile;
use App\Models\Task;
use App\Models\TaskReferenceFile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_protected_routes(): void
    {
        $this->get('/student/dashboard')->assertRedirect('/login');
        $this->get('/teacher/dashboard')->assertRedirect('/login');
    }

    public function test_role_middleware_prevents_student_from_accessing_teacher_routes(): void
    {
        $student = User::factory()->create(['role' => 'student']);

        $response = $this->actingAs($student)->get('/teacher/dashboard');

        $response->assertRedirect('/student/dashboard');
    }

    public function test_role_middleware_prevents_teacher_from_accessing_student_routes(): void
    {
        $teacher = User::factory()->create(['role' => 'teacher']);

        $response = $this->actingAs($teacher)->get('/student/dashboard');

        $response->assertRedirect('/teacher/dashboard');
    }

    public function test_file_download_blocks_unauthorized_users_with_403(): void
    {
        Storage::fake('local');

        $teacher = User::factory()->create(['role' => 'teacher']);
        $studentOwner = User::factory()->create(['role' => 'student']);
        $unauthorizedStudent = User::factory()->create(['role' => 'student']);

        $subject = Subject::create(['name' => 'Math', 'code' => 'MTH101', 'description' => 'Math Course']);
        $class = SchoolClass::create([
            'code' => 'MTH-101',
            'teacher_id' => $teacher->id,
            'subject_id' => $subject->id,
            'schedule_day' => 'Monday',
            'schedule_time' => '09:00 AM - 10:30 AM',
        ]);

        $task = Task::create([
            'class_id' => $class->id,
            'teacher_id' => $teacher->id,
            'title' => 'Homework 1',
            'due_date' => now()->addDays(5),
            'accepted_formats' => ['pdf'],
            'max_file_size_mb' => 10,
            'status' => 'active',
        ]);

        $submission = Submission::create([
            'task_id' => $task->id,
            'student_id' => $studentOwner->id,
            'submitted_at' => now(),
        ]);

        $submissionFile = SubmissionFile::create([
            'submission_id' => $submission->id,
            'file_path' => 'submissions/' . $task->id . '/test.pdf',
            'original_name' => 'test.pdf',
            'mime_type' => 'application/pdf',
            'size_bytes' => 1024,
        ]);

        Storage::disk('local')->put($submissionFile->file_path, 'dummy content');

        $this->actingAs($unauthorizedStudent)
            ->get(route('files.download', $submissionFile->id))
            ->assertStatus(403);

        $this->actingAs($studentOwner)
            ->get(route('files.download', $submissionFile->id))
            ->assertStatus(200);

        $this->actingAs($teacher)
            ->get(route('files.download', $submissionFile->id))
            ->assertStatus(200);
    }

    public function test_reference_file_download_blocks_non_enrolled_student(): void
    {
        Storage::fake('local');

        $teacher = User::factory()->create(['role' => 'teacher']);
        $nonEnrolledStudent = User::factory()->create(['role' => 'student']);

        $subject = Subject::create(['name' => 'Science', 'code' => 'SCI101', 'description' => 'Science Course']);
        $class = SchoolClass::create([
            'code' => 'SCI-101',
            'teacher_id' => $teacher->id,
            'subject_id' => $subject->id,
            'schedule_day' => 'Tuesday',
            'schedule_time' => '10:00 AM - 11:30 AM',
        ]);

        $task = Task::create([
            'class_id' => $class->id,
            'teacher_id' => $teacher->id,
            'title' => 'Project Guidelines',
            'due_date' => now()->addDays(7),
            'accepted_formats' => ['pdf'],
            'max_file_size_mb' => 10,
            'status' => 'active',
        ]);

        $refFile = TaskReferenceFile::create([
            'task_id' => $task->id,
            'original_name' => 'guidelines.pdf',
            'stored_path' => "tasks/{$task->id}/references/guidelines.pdf",
            'size_bytes' => 2048,
        ]);

        Storage::disk('local')->put($refFile->stored_path, 'sample reference content');

        $this->actingAs($nonEnrolledStudent)
            ->get(route('files.reference.download', $refFile->id))
            ->assertStatus(403);

        $this->actingAs($teacher)
            ->get(route('files.reference.download', $refFile->id))
            ->assertStatus(200);
    }

    public function test_upload_rate_limiter_throttles_excessive_requests(): void
    {
        $teacher = User::factory()->create(['role' => 'teacher']);
        $subject = Subject::create(['name' => 'Art', 'code' => 'ART101', 'description' => 'Art Course']);
        $class = SchoolClass::create([
            'code' => 'ART-101',
            'teacher_id' => $teacher->id,
            'subject_id' => $subject->id,
            'schedule_day' => 'Wednesday',
            'schedule_time' => '01:00 PM - 02:30 PM',
        ]);

        for ($i = 0; $i < 10; $i++) {
            $this->actingAs($teacher)->post(route('teacher.classes.tasks.store', $class->id), [
                'title' => "Task {$i}",
                'due_date' => now()->addDays(2)->format('Y-m-d'),
                'due_time' => '12:00',
            ]);
        }

        $response = $this->actingAs($teacher)->post(route('teacher.classes.tasks.store', $class->id), [
            'title' => 'Excess Task',
            'due_date' => now()->addDays(2)->format('Y-m-d'),
            'due_time' => '12:00',
        ]);

        $response->assertSessionHas('error', 'Too many attempts. Please wait a moment before trying again.');
    }
}
