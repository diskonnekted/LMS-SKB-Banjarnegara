<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('courses') || ! Schema::hasColumn('courses', 'grade_level')) {
            return;
        }

        DB::table('courses')
            ->whereNotNull('grade_level')
            ->where('grade_level', 'like', 'Kejar Paket%')
            ->update([
                'grade_level' => DB::raw("REPLACE(grade_level, 'Kejar Paket', 'Kesetaraan Paket')"),
            ]);
    }

    public function down(): void
    {
        if (! Schema::hasTable('courses') || ! Schema::hasColumn('courses', 'grade_level')) {
            return;
        }

        DB::table('courses')
            ->whereNotNull('grade_level')
            ->where('grade_level', 'like', 'Kesetaraan Paket%')
            ->update([
                'grade_level' => DB::raw("REPLACE(grade_level, 'Kesetaraan Paket', 'Kejar Paket')"),
            ]);
    }
};
