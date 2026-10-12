<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ClassAndTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teachers = User::where('role', 'teacher')->take(3)->get();
        $subjects = Subject::all();
        $paulo = User::where('name', 'Paulo Cordova')->first();

        if ($subjects->isEmpty()) {
            $this->command->warn('No subjects found. Please run SubjectSeeder first.');
            return;
        }

        $schedules = [
            ['day' => 'Monday/Wednesday', 'time' => '08:00 AM - 09:30 AM'],
            ['day' => 'Tuesday/Thursday', 'time' => '10:00 AM - 11:30 AM'],
            ['day' => 'Friday', 'time' => '01:00 PM - 04:00 PM'],
            ['day' => 'Saturday', 'time' => '09:00 AM - 12:00 NN'],
            ['day' => 'Monday/Wednesday', 'time' => '01:00 PM - 02:30 PM'],
        ];

        foreach ($teachers as $teacher) {
            // Give each teacher exactly 3 classes
            for ($i = 0; $i < 3; $i++) {
                $subject = $subjects->random();
                $schedule = $schedules[array_rand($schedules)];

                $schoolClass = SchoolClass::create([
                    'code' => strtoupper(Str::random(6)),
                    'teacher_id' => $teacher->id,
                    'subject_id' => $subject->id,
                    'schedule_day' => $schedule['day'],
                    'schedule_time' => $schedule['time'],
                ]);

                // Enroll Paulo to the first class of each teacher
                if ($i === 0 && $paulo) {
                    $schoolClass->students()->attach($paulo->id);
                }

                // Give each class 3 tasks with different statuses
                Task::create([
                    'class_id' => $schoolClass->id,
                    'teacher_id' => $teacher->id,
                    'title' => 'Midterm Project: ' . $subject->name,
                    'instructions' => 'Complete the required tasks for the midterm phase. Make sure to adhere to the naming conventions.',
                    'due_date' => now()->addWeeks(2),
                    'accepted_formats' => ['pdf', 'docx', 'zip'],
                    'max_file_size_mb' => 20,
                    'status' => 'active',
                ]);

                Task::create([
                    'class_id' => $schoolClass->id,
                    'teacher_id' => $teacher->id,
                    'title' => 'Initial Assessment: ' . $subject->name,
                    'instructions' => 'Please submit your preliminary findings for the subject.',
                    'due_date' => now()->subDays(3),
                    'accepted_formats' => ['pdf', 'docx'],
                    'max_file_size_mb' => 10,
                    'status' => 'active', // Will be rendered as overdue due to date
                ]);

                Task::create([
                    'class_id' => $schoolClass->id,
                    'teacher_id' => $teacher->id,
                    'title' => 'Final Exam: ' . $subject->name,
                    'instructions' => 'Comprehensive final examination covering all topics. Submit your final output compressed in a ZIP file.',
                    'due_date' => now()->addWeeks(6),
                    'accepted_formats' => ['zip', 'rar'],
                    'max_file_size_mb' => 50,
                    'status' => 'completed',
                ]);
            }
        }
    }
}
