<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quiz_attempt_answers', function (Blueprint $table) {
            $table->integer('score')->nullable()->after('is_correct');
        });

        // Backfill existing records
        DB::table('quiz_attempt_answers')->where('is_correct', true)->update(['score' => 100]);
        DB::table('quiz_attempt_answers')->where('is_correct', false)->update(['score' => 0]);
    }

    public function down(): void
    {
        Schema::table('quiz_attempt_answers', function (Blueprint $table) {
            $table->dropColumn('score');
        });
    }
};
