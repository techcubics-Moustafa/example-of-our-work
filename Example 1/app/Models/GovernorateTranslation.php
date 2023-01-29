<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class GovernorateTranslation extends Model implements Auditable
{
    use HasFactory, Sluggable, \OwenIt\Auditing\Auditable;

    protected $table = 'governorate_translations';

    protected $fillable = [
        'name', 'slug'
    ];

    public $timestamps = false;

    public function name(): Attribute
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
                'source' => 'name'
            ]
        ];
    }
}
