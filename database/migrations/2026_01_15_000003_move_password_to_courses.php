<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add password to courses
        Schema::table('courses', function (Blueprint $table) {
            $table->string('password')->nullable()->after('grade_level');
        });

        // Remove password from lessons
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn('password');
        });
    }

    public function down(): void
    {
        // Restore password to lessons
        Schema::table('lessons', function (Blueprint $table) {
            $table->string('password')->nullable()->after('title');
        });

        // Remove password from courses
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('password');
        });
    }
};
