<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->index('status');
            $table->index('due_date');
            $table->index('teacher_id');
        });

        Schema::table('submissions', function (Blueprint $table) {
            $table->index('submitted_at');
            $table->index('student_id');
        });

        Schema::table('classes', function (Blueprint $table) {
            $table->index('teacher_id');
            $table->index('subject_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['due_date']);
            $table->dropIndex(['teacher_id']);
        });

        Schema::table('submissions', function (Blueprint $table) {
            $table->dropIndex(['submitted_at']);
            $table->dropIndex(['student_id']);
        });

        Schema::table('classes', function (Blueprint $table) {
            $table->dropIndex(['teacher_id']);
            $table->dropIndex(['subject_id']);
        });
    }
};
