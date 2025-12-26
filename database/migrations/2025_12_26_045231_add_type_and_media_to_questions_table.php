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
        Schema::table('questions', function (Blueprint $table) {
            $table->string('type')->default('multiple_choice')->after('quiz_id');
            $table->string('media_url')->nullable()->after('question');
            $table->text('correct_answer')->change(); // Change to text to support longer answers/JSON
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn(['type', 'media_url']);
            $table->string('correct_answer')->change(); // Revert to string
        });
    }
};
