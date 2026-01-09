<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Database\Seeder;

class DummyCourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure we have a teacher
        $teacher = User::role('teacher')->first();
        if (! $teacher) {
            $teacher = User::create([
                'name' => 'Guru Teladan',
                'email' => 'guru@skb.com',
                'password' => bcrypt('password'),
            ]);
            $teacher->assignRole('teacher');
        }

        // Create Course
        // Use a unique slug to avoid duplication errors during multiple seed runs
        $slug = 'pemrograman-web-dasar-'.time();

        $course = Course::create([
            'title' => 'Pemrograman Web Dasar',
            'slug' => $slug,
            'description' => 'Pelajari dasar-dasar pembuatan website modern dengan HTML, CSS, dan JavaScript. Kursus ini dirancang untuk pemula.',
            'thumbnail' => null, // Placeholder or null
            'teacher_id' => $teacher->id,
            'is_published' => true,
        ]);

        // Module 1: HTML
        $module1 = Module::create([
            'course_id' => $course->id,
            'title' => 'Modul 1: Pengenalan HTML',
            'slug' => 'modul-1-pengenalan-html-'.time(),
            'order' => 1,
        ]);

        // Lesson 1.1: Article
        $lesson1 = Lesson::create([
            'module_id' => $module1->id,
            'title' => 'Apa itu HTML?',
            'slug' => 'apa-itu-html-'.time(),
            'type' => 'text',
            'content' => '<h1>Apa itu HTML?</h1><p>HTML adalah singkatan dari <strong>HyperText Markup Language</strong>. Ini adalah bahasa standar untuk membuat halaman web.</p><p>Dengan HTML, Anda dapat membuat struktur website Anda sendiri.</p>',
            'order' => 1,
        ]);

        // Lesson 1.2: Video
        $lesson2 = Lesson::create([
            'module_id' => $module1->id,
            'title' => 'Struktur Dasar HTML',
            'slug' => 'struktur-dasar-html-'.time(),
            'type' => 'video',
            'content' => 'https://www.youtube.com/embed/UB1O30fR-EE', // Example video
            'order' => 2,
        ]);

        // Quiz 1
        $quiz1 = Quiz::create([
            'lesson_id' => $lesson2->id,
            'title' => 'Kuis HTML Dasar',
            'passing_score' => 70,
        ]);

        // Questions for Quiz 1
        Question::create([
            'quiz_id' => $quiz1->id,
            'question' => 'Apa kepanjangan dari HTML?',
            'options' => json_encode(['HyperText Markup Language', 'HyperTool Multi Language', 'HighText Machine Language']),
            'correct_answer' => 'HyperText Markup Language',
        ]);

        Question::create([
            'quiz_id' => $quiz1->id,
            'question' => 'Tag mana yang digunakan untuk membuat paragraf?',
            'options' => json_encode(['<p>', '<par>', '<text>']),
            'correct_answer' => '<p>',
        ]);

        // Module 2: CSS
        $module2 = Module::create([
            'course_id' => $course->id,
            'title' => 'Modul 2: Styling dengan CSS',
            'slug' => 'modul-2-styling-dengan-css',
            'order' => 2,
        ]);

        // Lesson 2.1: PDF (Simulated link)
        $lesson3 = Lesson::create([
            'module_id' => $module2->id,
            'title' => 'Panduan CSS Lengkap',
            'slug' => 'panduan-css-lengkap',
            'type' => 'pdf',
            'content' => 'dummy-css-guide.pdf', // Normally a path
            'order' => 1,
        ]);

        // Quiz 2
        $quiz2 = Quiz::create([
            'lesson_id' => $lesson3->id,
            'title' => 'Kuis CSS Dasar',
            'passing_score' => 75,
        ]);

        Question::create([
            'quiz_id' => $quiz2->id,
            'question' => 'CSS adalah singkatan dari...',
            'options' => json_encode(['Cascading Style Sheets', 'Colorful Style Sheets', 'Computer Style Sheets']),
            'correct_answer' => 'Cascading Style Sheets',
        ]);

        $this->command->info('Dummy course "Pemrograman Web Dasar" created with modules, lessons, and quizzes.');
    }
}
