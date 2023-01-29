<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class BlogTranslation extends Model implements Auditable
{
    use HasFactory, Sluggable, \OwenIt\Auditing\Auditable;

    protected $table = 'blog_translations';

    protected $fillable = [
        'blog_id', 'title', 'slug', 'content', 'locale'
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
}
