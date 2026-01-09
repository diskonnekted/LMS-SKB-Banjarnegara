<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ImportOldLmsData extends Command
{
    protected $signature = 'import:old-lms';

    protected $description = 'Import data from old LearnPress database (skb_import)';

    protected $userMap = []; // old_id => new_id

    protected $courseMap = []; // old_id => new_id

    protected $sectionMap = []; // old_id => new_id

    protected $categoryMap = []; // old_term_taxonomy_id => new_id

    public function handle()
    {
        $this->info('Starting import...');

        // Configure the second database connection dynamically
        config(['database.connections.old_skb' => [
            'driver' => 'mysql',
            'host' => '127.0.0.1',
            'port' => '3306',
            'database' => 'skb_import',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => 'wp_',
            'strict' => false,
        ]]);

        try {
            DB::connection('old_skb')->getPdo();
            $this->info('Connected to old database successfully.');
        } catch (\Exception $e) {
            $this->error('Could not connect to old database: '.$e->getMessage());

            return 1;
        }

        DB::transaction(function () {
            $this->importAttachments();
            $this->importCategories();
            $this->importUsers();
            $this->importCourses();
            $this->importNews();
        });

        $this->info('Import completed successfully!');

        return 0;
    }

    private function importAttachments()
    {
        $this->info('Syncing Attachments...');

        $sourceDir = base_path('arsip/uploads');
        $destDir = storage_path('app/public/uploads');

        if (! File::exists($sourceDir)) {
            $this->warn("Source directory {$sourceDir} does not exist. Skipping attachment sync.");

            return;
        }

        if (! File::exists($destDir)) {
            File::makeDirectory($destDir, 0755, true);
        }

        File::copyDirectory($sourceDir, $destDir);
        $this->info("Attachments synced from {$sourceDir} to {$destDir}");
    }

    private function processContent($content)
    {
        if (empty($content)) {
            return $content;
        }

        // Replace old URLs with new local paths
        $search = [
            'https://lms.skb-banjarnegara.sch.id/wp-content/uploads/',
            'http://lms.skb-banjarnegara.sch.id/wp-content/uploads/',
            'https://skb-banjarnegara.sch.id/wp-content/uploads/',
            'http://skb-banjarnegara.sch.id/wp-content/uploads/',
        ];

        $replace = '/storage/uploads/';

        return str_replace($search, $replace, $content);
    }

    private function getFeaturedImage($postId)
    {
        $thumbnailId = DB::connection('old_skb')->table('postmeta')
            ->where('post_id', $postId)
            ->where('meta_key', '_thumbnail_id')
            ->value('meta_value');

        if ($thumbnailId) {
            $attachmentMeta = DB::connection('old_skb')->table('postmeta')
                ->where('post_id', $thumbnailId)
                ->where('meta_key', '_wp_attached_file')
                ->value('meta_value');

            if ($attachmentMeta) {
                return 'uploads/'.$attachmentMeta;
            }
        }

        return null;
    }

    private function importCategories()
    {
        $this->info('Importing Categories...');

        // Import Course Categories
        $courseCategories = DB::connection('old_skb')->table('terms as t')
            ->join('term_taxonomy as tt', 't.term_id', '=', 'tt.term_id')
            ->where('tt.taxonomy', 'course_category')
            ->select('t.*', 'tt.term_taxonomy_id')
            ->get();

        foreach ($courseCategories as $cat) {
            $newCat = Category::firstOrCreate(
                ['slug' => $cat->slug],
                ['name' => $cat->name]
            );
            $this->categoryMap[$cat->term_taxonomy_id] = $newCat->id;
        }

        // Import News Categories
        $newsCategories = DB::connection('old_skb')->table('terms as t')
            ->join('term_taxonomy as tt', 't.term_id', '=', 'tt.term_id')
            ->where('tt.taxonomy', 'category')
            ->select('t.*', 'tt.term_taxonomy_id')
            ->get();

        foreach ($newsCategories as $cat) {
            $newCat = Category::firstOrCreate(
                ['slug' => $cat->slug],
                ['name' => $cat->name]
            );
            $this->categoryMap[$cat->term_taxonomy_id] = $newCat->id;
        }
    }

    private function importUsers()
    {
        $this->info('Importing Users...');

        $oldUsers = DB::connection('old_skb')->table('users')->get();
        $bar = $this->output->createProgressBar($oldUsers->count());

        foreach ($oldUsers as $oldUser) {
            // Check if user exists by email
            $existingUser = User::where('email', $oldUser->user_email)->first();

            if ($existingUser) {
                $this->userMap[$oldUser->ID] = $existingUser->id;
                $bar->advance();

                continue;
            }

            // Create new user
            $newUser = User::create([
                'name' => $oldUser->display_name ?: $oldUser->user_login,
                'email' => $oldUser->user_email,
                'password' => Hash::make('skb12345'), // Default password
                'email_verified_at' => now(),
            ]);

            // Assign Roles
            $capabilities = DB::connection('old_skb')->table('usermeta')
                ->where('user_id', $oldUser->ID)
                ->where('meta_key', 'wp_capabilities')
                ->value('meta_value');

            if ($capabilities) {
                $roles = @unserialize($capabilities);
                if ($roles && is_array($roles)) {
                    if (isset($roles['administrator'])) {
                        $newUser->assignRole('admin');
                    } elseif (isset($roles['lp_teacher']) || isset($roles['editor']) || isset($roles['author'])) {
                        $newUser->assignRole('teacher');
                    } else {
                        $newUser->assignRole('student');
                    }
                } else {
                    $newUser->assignRole('student');
                }
            } else {
                $newUser->assignRole('student');
            }

            $this->userMap[$oldUser->ID] = $newUser->id;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
    }

    private function importNews()
    {
        $this->info('Importing News...');

        $oldPosts = DB::connection('old_skb')->table('posts')
            ->where('post_type', 'post')
            ->where('post_status', 'publish')
            ->get();

        $bar = $this->output->createProgressBar($oldPosts->count());

        foreach ($oldPosts as $oldPost) {
            // Check for duplicates
            if (\App\Models\News::where('slug', $oldPost->post_name)->exists()) {
                $bar->advance();

                continue;
            }

            $authorId = $this->userMap[$oldPost->post_author] ?? User::first()->id;

            // Get Category
            $categoryId = null;
            $termRel = DB::connection('old_skb')->table('term_relationships as tr')
                ->join('term_taxonomy as tt', 'tr.term_taxonomy_id', '=', 'tt.term_taxonomy_id')
                ->where('tr.object_id', $oldPost->ID)
                ->where('tt.taxonomy', 'category')
                ->first();

            if ($termRel) {
                $categoryId = $this->categoryMap[$termRel->term_taxonomy_id] ?? null;
            }

            \App\Models\News::create([
                'title' => $oldPost->post_title,
                'slug' => $oldPost->post_name,
                'content' => $this->processContent($oldPost->post_content),
                'published_at' => $oldPost->post_date,
                'user_id' => $authorId,
                'category_id' => $categoryId,
                'is_published' => true,
                'thumbnail' => $this->getFeaturedImage($oldPost->ID),
            ]);

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
    }

    private function importCourses()
    {
        $this->info('Importing Courses...');

        // Fetch published courses
        $oldCourses = DB::connection('old_skb')->table('posts')
            ->where('post_type', 'lp_course')
            ->where('post_status', 'publish')
            ->get();

        $bar = $this->output->createProgressBar($oldCourses->count());

        foreach ($oldCourses as $oldCourse) {
            $teacherId = $this->userMap[$oldCourse->post_author] ?? User::role('teacher')->first()->id ?? User::first()->id;

            // Handle duplicate slugs
            $slug = $oldCourse->post_name;
            $originalSlug = $slug;
            $count = 1;
            while (Course::where('slug', $slug)->exists()) {
                $slug = $originalSlug.'-'.$count++;
            }

            // Get Category
            $categoryId = null;
            $termRel = DB::connection('old_skb')->table('term_relationships as tr')
                ->join('term_taxonomy as tt', 'tr.term_taxonomy_id', '=', 'tt.term_taxonomy_id')
                ->where('tr.object_id', $oldCourse->ID)
                ->where('tt.taxonomy', 'course_category')
                ->first();

            if ($termRel) {
                $categoryId = $this->categoryMap[$termRel->term_taxonomy_id] ?? null;
            }

            // Fallback category if none found
            if (! $categoryId) {
                $categoryId = Category::firstOrCreate(['name' => 'Umum', 'slug' => 'umum'])->id;
            }

            $newCourse = Course::create([
                'title' => $oldCourse->post_title,
                'slug' => $slug,
                'description' => $this->processContent($oldCourse->post_content),
                'teacher_id' => $teacherId,
                'is_published' => true,
                'price' => 0,
                'level' => 'beginner',
                'category_id' => $categoryId,
                'thumbnail' => $this->getFeaturedImage($oldCourse->ID),
            ]);

            $this->courseMap[$oldCourse->ID] = $newCourse->id;

            // Import Sections (Modules)
            $this->importSections($oldCourse->ID, $newCourse->id);

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
    }

    private function importSections($oldCourseId, $newCourseId)
    {
        $oldSections = DB::connection('old_skb')->table('learnpress_sections')
            ->where('section_course_id', $oldCourseId)
            ->orderBy('section_order')
            ->get();

        foreach ($oldSections as $oldSection) {
            $newModule = Module::create([
                'course_id' => $newCourseId,
                'title' => $oldSection->section_name,
                'slug' => Str::slug($oldSection->section_name).'-'.uniqid(),
                'order' => $oldSection->section_order,
                'is_published' => true,
            ]);

            $this->importSectionItems($oldSection->section_id, $newModule->id);
        }
    }

    private function importSectionItems($oldSectionId, $newModuleId)
    {
        // LearnPress stores items in learnpress_section_items
        $items = DB::connection('old_skb')->table('learnpress_section_items')
            ->where('section_id', $oldSectionId)
            ->orderBy('item_order')
            ->get();

        foreach ($items as $item) {
            // Get item details from posts
            $post = DB::connection('old_skb')->table('posts')
                ->where('ID', $item->item_id)
                ->first();

            if (! $post) {
                continue;
            }

            if ($post->post_type === 'lp_lesson') {
                Lesson::create([
                    'module_id' => $newModuleId,
                    'title' => $post->post_title,
                    'slug' => $post->post_name.'-'.uniqid(),
                    'content' => $this->processContent($post->post_content),
                    'is_published' => true,
                    'is_free' => false,
                    'order' => $item->item_order,
                    'type' => 'text', // Default to text
                ]);
            } elseif ($post->post_type === 'lp_quiz') {
                // Handle Quiz as a Lesson Wrapper
                $newLesson = Lesson::create([
                    'module_id' => $newModuleId,
                    'title' => $post->post_title,
                    'slug' => $post->post_name.'-'.uniqid(),
                    'content' => 'Silakan kerjakan kuis di bawah ini.',
                    'is_published' => true,
                    'is_free' => false,
                    'order' => $item->item_order,
                    'type' => 'text',
                ]);

                // Import Quiz
                $this->importQuiz($post->ID, $newLesson->id);
            }
        }
    }

    private function importQuiz($oldQuizId, $newLessonId)
    {
        $oldQuizPost = DB::connection('old_skb')->table('posts')->where('ID', $oldQuizId)->first();
        if (! $oldQuizPost) {
            return;
        }

        $newQuiz = \App\Models\Quiz::create([
            'lesson_id' => $newLessonId,
            'title' => $oldQuizPost->post_title,
            'passing_score' => 70, // Default, hard to find in meta easily without key
        ]);

        // Import Questions
        // wpy4_learnpress_quiz_questions maps quiz_id to question_id
        $quizQuestions = DB::connection('old_skb')->table('learnpress_quiz_questions')
            ->where('quiz_id', $oldQuizId)
            ->orderBy('question_order')
            ->get();

        foreach ($quizQuestions as $qq) {
            $this->importQuestion($qq->question_id, $newQuiz->id);
        }
    }

    private function importQuestion($oldQuestionId, $newQuizId)
    {
        $oldQuestionPost = DB::connection('old_skb')->table('posts')->where('ID', $oldQuestionId)->first();
        if (! $oldQuestionPost) {
            return;
        }

        // Fetch Answers
        $answers = DB::connection('old_skb')->table('learnpress_question_answers')
            ->where('question_id', $oldQuestionId)
            ->orderBy('order')
            ->get();

        $options = [];
        $correctAnswer = '';

        $letters = range('a', 'z');
        $i = 0;

        foreach ($answers as $ans) {
            $key = $letters[$i] ?? $i;
            $options[$key] = $ans->title;

            if ($ans->is_true === 'yes' || $ans->is_true === 'on' || $ans->is_true == 1) {
                $correctAnswer = $key;
            }
            $i++;
        }

        // If no correct answer found, default to 'a'
        if (empty($correctAnswer) && ! empty($options)) {
            $correctAnswer = array_key_first($options);
        }

        if (empty($options)) {
            $options = ['a' => 'True', 'b' => 'False'];
            $correctAnswer = 'a';
        }

        \App\Models\Question::create([
            'quiz_id' => $newQuizId,
            'question' => $oldQuestionPost->post_title.' '.$this->processContent($oldQuestionPost->post_content),
            'options' => $options, // Casts to JSON automatically in model?
            // Need to check Question model casts
            'correct_answer' => $correctAnswer,
        ]);
    }
}
