<?php

use App\Models\Course;
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
            ->select(['id', 'grade_level'])
            ->whereNotNull('grade_level')
            ->orderBy('id')
            ->chunkById(200, function ($rows) {
                foreach ($rows as $row) {
                    $normalized = Course::normalizeGradeLevel($row->grade_level);
                    if ($normalized !== null && $normalized !== $row->grade_level) {
                        DB::table('courses')->where('id', $row->id)->update(['grade_level' => $normalized]);
                    }
                }
            });
    }

    public function down(): void {}
};
