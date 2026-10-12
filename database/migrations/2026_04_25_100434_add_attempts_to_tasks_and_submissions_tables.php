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
            $table->integer('max_attempts')->default(1)->after('max_file_size_mb');
        });

        Schema::table('submissions', function (Blueprint $table) {
            $table->integer('attempts_count')->default(1)->after('student_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('max_attempts');
        });

        Schema::table('submissions', function (Blueprint $table) {
            $table->dropColumn('attempts_count');
        });
    }
};
