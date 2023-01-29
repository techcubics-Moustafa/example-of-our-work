<?php

namespace App\Models;

use App\Enums\Status;
use App\Helpers\Setting\Utility;
use App\QueryFilters\BlogCategory\BlogQueryFilter;
use App\QueryFilters\Sort\SortFilter;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pipeline\Pipeline;
use OwenIt\Auditing\Contracts\Auditable;

class Blog extends Model implements TranslatableContract, Auditable
{
    use HasFactory, Translatable, \OwenIt\Auditing\Auditable;

    protected $table = 'blogs';

    protected $fillable = [
        'default_image', 'category_id', 'status'
    ];

    protected $with = ['translations'];

    protected $translationForeignKey = 'blog_id';

    public $translatedAttributes = [
        'title', 'slug', 'content'
    ];

    /* Pipeline Query Filter */
    public static function allBlogs()
    {
        return app(Pipeline::class)
            ->send(Blog::query()->with(['category']))
            ->through([
                SortFilter::class,
                BlogQueryFilter::class,
            ])
            ->thenReturn()
            ->paginate(Utility::getValByName('pagination_limit'))->withQueryString();
    }

    /* scope */
    public function scopeStatus($query, $status = Status::Active)
    {
        return $query->where('status', $status);
    }

    public function images(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(File::class, 'relationable');
    }

    public function image(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(File::class, 'relationable')->oldestOfMany();
    }

    public function tags(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BlogTag::class, 'blog_id');
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }
}
