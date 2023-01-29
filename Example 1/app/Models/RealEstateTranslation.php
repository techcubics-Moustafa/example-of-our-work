<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class RealEstateTranslation extends Model implements Auditable
{
    use HasFactory, Sluggable, \OwenIt\Auditing\Auditable;

    protected $table = 'real_estate_translations';

    protected $fillable = [
        'title', 'slug', 'description', 'content', 'address', 'seo_title', 'seo_description'
    ];

    public $timestamps = false;

    public function title(): Attribute
    {
        return new Attribute(
            get: fn($value) => ucfirst($value),
            set: fn($value) => ucfirst($value),
        );
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
}
