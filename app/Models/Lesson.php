<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = [
        'module_id', 'title', 'slug', 'type', 'content', 'basic_competency', 'learning_objectives', 'file_path', 'order'
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function quiz()
    {
        return $this->hasOne(Quiz::class);
    }
    
    public function usersCompleted()
    {
        return $this->belongsToMany(User::class, 'lesson_user')->withPivot('completed');
    }
}
