<?php

namespace Tests\Feature;

use App\Models\Exam;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TeacherExamTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_can_create_exam_add_question_and_student_can_submit_with_points(): void
    {
        Role::create(['name' => 'student']);
        Role::create(['name' => 'teacher']);

        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');

        $student = User::factory()->create();
        $student->assignRole('student');

        $this->actingAs($teacher)
            ->post(route('teacher.exams.store', absolute: false), [
                'title' => 'UAS Matematika',
                'description' => 'Ujian akhir semester',
                'passing_score' => 70,
                'is_published' => 1,
            ])
            ->assertRedirect();

        $exam = Exam::query()->firstOrFail();
        $this->assertSame($teacher->id, $exam->teacher_id);

        $this->actingAs($teacher)
            ->post(route('teacher.exams.questions.store', $exam, absolute: false), [
                'type' => 'multiple_choice',
                'question' => '1 + 1 = ?',
                'points' => 3,
                'order' => 1,
                'option_a' => '1',
                'option_b' => '2',
                'correct_answer' => 'b',
            ])
            ->assertRedirect();

        $this->assertDatabaseCount('exam_questions', 1);

        $this->actingAs($student)
            ->get(route('exams.take', $exam->access_code, absolute: false))
            ->assertOk();

        $questionId = (int) $exam->questions()->value('id');

        $this->actingAs($student)
            ->post(route('exams.submit', $exam->access_code, absolute: false), [
                'q_'.$questionId => 'b',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('exam_attempts', [
            'user_id' => $student->id,
            'exam_id' => $exam->id,
            'earned_points' => 3,
            'total_points' => 3,
            'score' => 100,
        ]);
    }

    public function test_exam_grade_level_is_saved_and_normalized(): void
    {
        Role::create(['name' => 'student']);
        Role::create(['name' => 'teacher']);

        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');

        $this->actingAs($teacher)
            ->post(route('teacher.exams.store', absolute: false), [
                'title' => 'UAS Paket C 11',
                'description' => null,
                'grade_level' => 'Paket C Kelas 11',
                'passing_score' => 70,
                'is_published' => 1,
            ])
            ->assertRedirect();

        $exam = Exam::query()->firstOrFail();
        $this->assertSame('Kesetaraan Paket C Kelas 11', $exam->grade_level);
    }

    public function test_exam_can_save_course_id(): void
    {
        Role::create(['name' => 'student']);
        Role::create(['name' => 'teacher']);

        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');

        $course = \App\Models\Course::create([
            'title' => 'IPS Paket C 11',
            'slug' => Str::slug('IPS Paket C 11'),
            'description' => null,
            'thumbnail' => null,
            'teacher_id' => $teacher->id,
            'is_published' => true,
            'grade_level' => 'Kesetaraan Paket C Kelas 11',
            'category_id' => null,
            'basic_competency' => null,
            'learning_objectives' => null,
        ]);

        $this->actingAs($teacher)
            ->post(route('teacher.exams.store', absolute: false), [
                'title' => 'UAS IPS',
                'course_id' => $course->id,
                'passing_score' => 70,
                'is_published' => 1,
            ])
            ->assertRedirect();

        $exam = Exam::query()->firstOrFail();
        $this->assertSame($course->id, $exam->course_id);
        $this->assertSame('Kesetaraan Paket C Kelas 11', $exam->grade_level);
    }

    public function test_teacher_can_submit_question_with_separate_correct_answer_fields(): void
    {
        Role::create(['name' => 'teacher']);

        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');

        $this->actingAs($teacher)
            ->post(route('teacher.exams.store', absolute: false), [
                'title' => 'Ujian',
                'passing_score' => 70,
                'is_published' => 1,
            ])
            ->assertRedirect();

        $exam = Exam::query()->firstOrFail();

        $this->actingAs($teacher)
            ->post(route('teacher.exams.questions.store', $exam, absolute: false), [
                'type' => 'multiple_choice',
                'question' => '1 + 1 = ?',
                'points' => 1,
                'option_a' => '1',
                'option_b' => '2',
                'correct_answer_mc' => 'b',
                'correct_answer_tf' => 'true',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('exam_questions', [
            'exam_id' => $exam->id,
            'type' => 'multiple_choice',
            'correct_answer' => 'b',
        ]);

        $this->actingAs($teacher)
            ->post(route('teacher.exams.questions.store', $exam, absolute: false), [
                'type' => 'true_false',
                'question' => 'Langit biru?',
                'points' => 1,
                'correct_answer_tf' => 'false',
                'correct_answer_mc' => 'a',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('exam_questions', [
            'exam_id' => $exam->id,
            'type' => 'true_false',
            'correct_answer' => 'false',
        ]);
    }
}
