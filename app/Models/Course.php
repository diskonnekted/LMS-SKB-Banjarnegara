<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'thumbnail',
        'teacher_id',
        'is_published',
        'category_id',
        'grade_level',
        'basic_competency',
        'learning_objectives',
    ];

    public static function normalizeGradeLevel(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim(preg_replace('/\s+/', ' ', $value));
        if ($value === '') {
            return null;
        }

        if (preg_match('/^(?:Kejar|Kesetaraan)\s*Paket\s*([ABC])\s*(?:-|)\s*Kelas\s*(\d{1,2})$/i', $value, $m)) {
            $paket = strtoupper($m[1]);
            $kelas = (int) $m[2];

            return "Kesetaraan Paket {$paket} Kelas {$kelas}";
        }

        if (preg_match('/^Paket\s*([ABC])\s*(?:-|)\s*Kelas\s*(\d{1,2})$/i', $value, $m)) {
            $paket = strtoupper($m[1]);
            $kelas = (int) $m[2];

            return "Kesetaraan Paket {$paket} Kelas {$kelas}";
        }

        if (preg_match('/^(?:(?:Kejar|Kesetaraan)\s*)?Paket\s*([ABC])$/i', $value, $m)) {
            $paket = strtoupper($m[1]);

            return "Kesetaraan Paket {$paket}";
        }

        if (preg_match('/^Kelas\s*(\d{1,2})(?:\s*(SD|SMP|SMA))?$/i', $value, $m)) {
            $kelas = (int) $m[1];
            $suffix = isset($m[2]) ? strtoupper($m[2]) : null;

            $paket = null;
            if ($suffix === 'SD') {
                $paket = 'A';
            } elseif ($suffix === 'SMP') {
                $paket = 'B';
            } elseif ($suffix === 'SMA') {
                $paket = 'C';
            } elseif ($kelas >= 3 && $kelas <= 6) {
                $paket = 'A';
            } elseif ($kelas >= 7 && $kelas <= 9) {
                $paket = 'B';
            } elseif ($kelas >= 10 && $kelas <= 12) {
                $paket = 'C';
            }

            if ($paket !== null) {
                return "Kesetaraan Paket {$paket} Kelas {$kelas}";
            }
        }

        return $value;
    }

    public function setGradeLevelAttribute($value): void
    {
        $this->attributes['grade_level'] = self::normalizeGradeLevel(is_string($value) ? $value : null);
    }

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
