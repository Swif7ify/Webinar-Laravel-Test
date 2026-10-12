<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    protected $fillable = [
        'class_id',
        'teacher_id',
        'title',
        'instructions',
        'due_date',
        'accepted_formats',
        'max_file_size_mb',
        'status',
    ];
    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    public function userSubmission(int $studentId)
    {
        return $this->submissions()->where('student_id', $studentId)->first();
    }
    protected $casts = [
        'accepted_formats'  => 'array',
        'due_date'          => 'datetime',
    ];

    public function getStatusAttribute(string $value): string
    {
        if (in_array($value, ['closed', 'completed'])) {
            return $value;
        }

        return $this->due_date->isPast() ? 'closed' : 'active';
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function referenceFiles(): HasMany
    {
        return $this->hasMany(TaskReferenceFile::class);
    }
}
