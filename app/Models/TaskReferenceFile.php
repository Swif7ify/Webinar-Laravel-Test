<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskReferenceFile extends Model
{
    protected $fillable = ['task_id', 'original_name', 'stored_path', 'size_bytes'];
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
