<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;

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

    protected function publishedAt(): Attribute {
        return Attribute::make(
            get: fn ($value) => $value ? Carbon::parse($value)->format('Y-m-d') : $value
        );
    }

    protected function createdAt(): Attribute {
        return Attribute::make(
            get: fn ($value) => $value ? Carbon::parse($value)->format('Y-m-d') : $value
        );
    }
}
