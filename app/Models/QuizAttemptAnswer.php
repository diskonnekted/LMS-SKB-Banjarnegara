<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAttemptAnswer extends Model
{
    protected $fillable = ['quiz_attempt_id', 'question_id', 'answer', 'is_correct', 'score'];

    protected $casts = [
        'is_correct' => 'boolean',
        'score' => 'integer',
    ];

    public function attempt()
    {
        return $this->belongsTo(QuizAttempt::class, 'quiz_attempt_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
