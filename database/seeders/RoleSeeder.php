<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Create Roles
        $adminRole = Role::create(['name' => 'admin']);
        $teacherRole = Role::create(['name' => 'teacher']);
        $studentRole = Role::create(['name' => 'student']);

        // Create Admin
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@skb.com',
            'password' => Hash::make('password'),
            'bio' => 'System Administrator',
        ]);
        $admin->assignRole($adminRole);

        // Create Teacher
        $teacher = User::create([
            'name' => 'Mr. Teacher',
            'email' => 'teacher@skb.com',
            'password' => Hash::make('password'),
            'bio' => 'Experienced Math Tutor',
        ]);
        $teacher->assignRole($teacherRole);

        // Create Student
        $student = User::create([
            'name' => 'John Doe',
            'email' => 'student@skb.com',
            'password' => Hash::make('password'),
            'bio' => 'Eager learner',
        ]);
        $student->assignRole($studentRole);
    }
}
