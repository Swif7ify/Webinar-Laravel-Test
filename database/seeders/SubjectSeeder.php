<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            'Introduction to Computing',
            'Computer Programming 1',
            'Computer Programming 2',
            'Data Structures and Algorithms',
            'Object-Oriented Programming',
            'Web Development',
            'Database Management Systems',
            'Operating Systems',
            'Computer Networks',
            'Software Engineering',
            'Information Assurance and Security',
            'Capstone Project 1',
            'Capstone Project 2',
            'Mobile Application Development',
            'Artificial Intelligence',
            'Human-Computer Interaction',
            'Systems Analysis and Design',
            'Discrete Mathematics',
            'Technical Writing in IT',
            'IT Project Management',
        ];

        foreach ($subjects as $name) {
            Subject::firstOrCreate(['name' => $name]);
        }
    }
}
