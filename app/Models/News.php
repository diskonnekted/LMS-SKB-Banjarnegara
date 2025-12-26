<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $table = 'posts';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'thumbnail',
        'is_published',
    ];

    public function user()
    {
        return $this->belongsTo(User::class); // Migration didn't add user_id to posts, let's check.
    }
}
