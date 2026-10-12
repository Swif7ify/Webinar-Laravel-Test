<?php

namespace Database\Seeders;

use App\Models\User;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        User::create([
            'name' => 'Aaron Paul Sario',
            'email' => 'sario@onedev.com',
            'password' => Hash::make('teacher123'),
            'role' => 'teacher',
            'is_approved' => true,
        ]);

        User::create([
            'name' => 'Nikolai Roman Nepomuceno',
            'email' => 'nikolai@onedev.com',
            'password' => Hash::make('teacher123'),
            'role' => 'teacher',
            'is_approved' => true,
        ]);

        User::create([
            'name' => 'Earl Ordovez',
            'email' => 'earl@onedev.com',
            'password' => Hash::make('teacher123'),
            'role' => 'teacher',
            'is_approved' => true,
        ]);

        // Student role Users
        User::create([
            'name' => 'Paulo Cordova',
            'email' => 'paulo@onedev.com',
            'password' => Hash::make('student123'),
            'role' => 'student',
            'is_approved' => true,
        ]);

        User::create([
            'name' => 'Seann Patrick Tamondong',
            'email' => 'seann@onedev.com',
            'password' => Hash::make('student123'),
            'role' => 'student',
            'is_approved' => true,
        ]);

        User::create([
            'name' => 'Johannes Randhall De Jesus',
            'email' => 'johannes@onedev.com',
            'password' => Hash::make('student123'),
            'role' => 'student',
            'is_approved' => true,
        ]);

        User::create([
            'name' => 'Marina Camaso',
            'email' => 'marina@onedev.com',
            'password' => Hash::make('student123'),
            'role' => 'student',
            'is_approved' => true,
        ]);

        User::create([
            'name' => 'Ridley Angeles',
            'email' => 'ridley@onedev.com',
            'password' => Hash::make('student123'),
            'role' => 'student',
            'is_approved' => true,
        ]);

        User::create([
            'name' => 'Paul Jan Dilag',
            'email' => 'paul@onedev.com',
            'password' => Hash::make('student123'),
            'role' => 'student',
            'is_approved' => true,
        ]);

        User::create([
            'name' => 'Aaron Jan Estacio',
            'email' => 'aaronJan@onedev.com',
            'password' => Hash::make('student123'),
            'role' => 'student',
            'is_approved' => true,
        ]);

        User::create([
            'name' => 'Catherine Mon',
            'email' => 'catherine@onedev.com',
            'password' => Hash::make('student123'),
            'role' => 'student',
            'is_approved' => true,
        ]);

        User::create([
            'name' => 'Aljhun Abanes',
            'email' => 'aljhun@onedev.com',
            'password' => Hash::make('student123'),
            'role' => 'student',
            'is_approved' => true,
        ]);

        User::create([
            'name' => 'Kent Ann Ecal',
            'email' => 'kentAnn@onedev.com',
            'password' => Hash::make('student123'),
            'role' => 'student',
            'is_approved' => true,
        ]);

        User::create([
            'name' => 'Rosary Garcia',
            'email' => 'rosary@onedev.com',
            'password' => Hash::make('student123'),
            'role' => 'student',
            'is_approved' => true,
        ]);
    }

}
