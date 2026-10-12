<?php

namespace App\Http\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserRepository
{
    public function getStudents(): Collection
    {
        return User::where('role', 'student')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);
    }
}
