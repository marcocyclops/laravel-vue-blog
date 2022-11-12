<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Tags\HasTags;

use function PHPSTORM_META\map;

class Post extends Model
{
    use HasFactory, HasTags;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'published',
        'published_at',
        'tags'
    ];

    protected $casts = [
        'published' => 'boolean',
        'published_at' => 'date'
    ];
}
