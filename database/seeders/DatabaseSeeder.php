<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\News;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(CategorySeeder::class);

        // Create Roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $teacherRole = Role::firstOrCreate(['name' => 'teacher']);
        $studentRole = Role::firstOrCreate(['name' => 'student']);

        // Create Users
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@skb.com',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole($adminRole);

        $teacher = User::factory()->create([
            'name' => 'Guru Teladan',
            'email' => 'guru@skb.com',
            'password' => bcrypt('password'),
        ]);
        $teacher->assignRole($teacherRole);

        $student = User::factory()->create([
            'name' => 'Siswa Belajar',
            'email' => 'student@skb.com',
            'password' => bcrypt('password'),
        ]);
        $student->assignRole($studentRole);

        // Create Dummy Course
        $course = Course::create([
            'title' => 'Introduction to Laravel',
            'slug' => 'introduction-to-laravel',
            'description' => 'Learn the basics of Laravel framework.',
            'teacher_id' => $teacher->id,
            'is_published' => true,
        ]);

        $module = Module::create([
            'course_id' => $course->id,
            'title' => 'Getting Started',
            'order' => 1,
        ]);

        Lesson::create([
            'module_id' => $module->id,
            'title' => 'Installation',
            'slug' => 'installation',
            'type' => 'text',
            'content' => '<p>Here is how you install Laravel...</p>',
            'order' => 1,
        ]);

        Lesson::create([
            'module_id' => $module->id,
            'title' => 'Configuration',
            'slug' => 'configuration',
            'type' => 'text',
            'content' => '<p>Configuration is key...</p>',
            'order' => 2,
        ]);

        // Enroll Student
        $student->enrolledCourses()->attach($course->id);

        // Create Dummy News
        News::create([
            'title' => 'Welcome to the New LMS',
            'slug' => 'welcome-new-lms',
            'content' => '<p>We are excited to launch our new LMS...</p>',
            'is_published' => true,
            'user_id' => $admin->id, // If I added user_id to table, otherwise remove this line.
        ]);

        $this->call(DummyCourseSeeder::class);
    }
}
