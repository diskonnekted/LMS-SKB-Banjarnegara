<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Exam extends Model
{
    protected $fillable = [
        'teacher_id',
        'course_id',
        'title',
        'description',
        'grade_level',
        'access_code',
        'passing_score',
        'duration_minutes',
        'starts_at',
        'ends_at',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $exam) {
            if ($exam->access_code) {
                return;
            }

            do {
                $code = Str::upper(Str::random(8));
            } while (self::query()->where('access_code', $code)->exists());

            $exam->access_code = $code;
        });
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function questions()
    {
        return $this->hasMany(ExamQuestion::class);
    }

    public function attempts()
    {
        return $this->hasMany(ExamAttempt::class);
    }

    public function isOpenNow(): bool
    {
        $now = now();
        if ($this->starts_at && $now->lt($this->starts_at)) {
            return false;
        }
        if ($this->ends_at && $now->gt($this->ends_at)) {
            return false;
        }

        return true;
    }

    public function setGradeLevelAttribute($value): void
    {
        $value = is_string($value) ? trim($value) : null;
        $this->attributes['grade_level'] = $value === '' ? null : Course::normalizeGradeLevel($value);
    }
}
