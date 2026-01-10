<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->date('date_of_birth')->nullable()->after('email_verified_at');
            $table->string('place_of_birth')->nullable()->after('date_of_birth');
            $table->string('gender', 20)->nullable()->after('place_of_birth');
            $table->string('grade_level')->nullable()->after('gender');
            $table->string('whatsapp_number', 30)->nullable()->after('grade_level');
            $table->string('school_name')->nullable()->after('whatsapp_number');
            $table->string('nisn', 30)->nullable()->after('school_name');
            $table->string('nik', 30)->nullable()->after('nisn');
            $table->text('address')->nullable()->after('nik');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'date_of_birth',
                'place_of_birth',
                'gender',
                'grade_level',
                'whatsapp_number',
                'school_name',
                'nisn',
                'nik',
                'address',
            ]);
        });
    }
};
