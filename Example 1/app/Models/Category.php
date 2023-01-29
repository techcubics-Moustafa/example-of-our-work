<?php

namespace App\Models;

use App\Helpers\Setting\Utility;
use App\QueryFilters\Category\CategoryQueryFilter;
use App\QueryFilters\Name\NameQueryFilter;
use App\QueryFilters\Sort\SortFilter;
use App\Traits\Scopes\ScopeStatus;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pipeline\Pipeline;
use OwenIt\Auditing\Contracts\Auditable;

class Category extends Model implements TranslatableContract, Auditable
{
    use HasFactory, Translatable, \OwenIt\Auditing\Auditable, ScopeStatus;

    protected $table = 'categories';

    protected $fillable = [
        'ranking', 'parent_id', 'image', 'status'
    ];

    protected $with = ['translations'];

    protected $translationForeignKey = 'category_id';

    public $translatedAttributes = [
        'name', 'slug'
    ];

    /* Pipeline Query Filter */
    public static function allCategories($categories)
    {
        return app(Pipeline::class)
            ->send($categories)
            ->through([
                SortFilter::class,
                NameQueryFilter::class,
                CategoryQueryFilter::class,
            ])
            ->thenReturn()
            ->paginate(Utility::getValByName('pagination_limit'))
            ->withQueryString();
    }

    /* scopes */


    /* relations */

    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

}
