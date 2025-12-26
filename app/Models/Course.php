<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'title', 'slug', 'description', 'thumbnail', 'teacher_id', 'is_published', 'category_id', 'grade_level', 'basic_competency', 'learning_objectives'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function modules()
    {
        return $this->hasMany(Module::class)->orderBy('order');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'course_user')
                    ->withPivot('enrolled_at', 'completed_at')
                    ->withTimestamps();
    }
}
