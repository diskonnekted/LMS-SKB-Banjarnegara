<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\Lesson;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Database\Seeder;

class AssignmentTestSeeder extends Seeder
{
    public function run(): void
    {
        $lesson = Lesson::first();
        if (!$lesson) {
            $this->command->warn('No lessons found. Cannot create test data.');
            return;
        }

        // Create assignment
        $assignment = Assignment::create([
            'lesson_id' => $lesson->id,
            'title' => 'Tugas Contoh: Analisis Materi',
            'description' => 'Baca materi pelajaran ini dan buat ringkasan dalam bentuk PDF. Upload hasilnya di sini.',
            'due_date' => now()->addDays(7),
            'file_size_limit' => 10,
            'is_active' => true,
        ]);

        $this->command->info("Assignment created: {$assignment->id}");

        // Create student submission
        $student = User::role('student')->first();
        if ($student) {
            $submission = Submission::create([
                'assignment_id' => $assignment->id,
                'user_id' => $student->id,
                'file_path' => 'submissions/test-file.pdf',
                'notes' => 'Ini adalah contoh submission.',
                'status' => 'pending',
            ]);

            $this->command->info("Submission created: {$submission->id} for student: {$student->name}");
        }
    }
}
