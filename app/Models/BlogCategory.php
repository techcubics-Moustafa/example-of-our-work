<?php

namespace App\Models;

use App\Enums\Status;
use App\Helpers\Setting\Utility;
use App\QueryFilters\BlogCategory\BlogCategoryQueryFilter;
use App\QueryFilters\Name\NameQueryFilter;
use App\QueryFilters\Sort\SortFilter;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pipeline\Pipeline;
use OwenIt\Auditing\Contracts\Auditable;

class BlogCategory extends Model implements TranslatableContract, Auditable
{
    use HasFactory, Translatable, \OwenIt\Auditing\Auditable;

    protected $table = 'blog_categories';

    protected $fillable = [
        'image', 'status'
    ];

    protected $with = ['translations'];

    protected $translationForeignKey = 'category_id';

    public $translatedAttributes = [
        'name', 'slug'
    ];

    /* Pipeline Query Filter */
    public static function allCategories()
    {
        return app(Pipeline::class)
            ->send(BlogCategory::query())
            ->through([
                SortFilter::class,
                NameQueryFilter::class,
                BlogCategoryQueryFilter::class,
            ])
            ->thenReturn()
            ->paginate(Utility::getValByName('pagination_limit'))->withQueryString();
    }

    /* scope */
    public function scopeStatus($query, $status = Status::Active)
    {
        return $query->where('status', $status);
    }

    public function blogs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Blog::class, 'category_id');
    }
}
