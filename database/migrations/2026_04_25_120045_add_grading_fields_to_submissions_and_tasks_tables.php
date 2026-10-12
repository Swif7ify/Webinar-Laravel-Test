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
            $table->unsignedInteger('points')->default(100)->after('instructions');
        });

        Schema::table('submissions', function (Blueprint $table) {
            $table->decimal('grade', 5, 2)->nullable()->after('attempts_count');
            $table->text('feedback')->nullable()->after('grade');
            $table->string('status', 20)->default('pending')->after('feedback');
            $table->timestamp('graded_at')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('points');
        });

        Schema::table('submissions', function (Blueprint $table) {
            $table->dropColumn(['grade', 'feedback', 'status', 'graded_at']);
        });
    }
};
