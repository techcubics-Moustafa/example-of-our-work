<?php

namespace App\Models;

use App\Helpers\Setting\Utility;
use App\QueryFilters\Feature\FeatureQueryFilter;
use App\QueryFilters\Name\NameQueryFilter;
use App\QueryFilters\Sort\SortFilter;
use App\Traits\Scopes\ScopeStatus;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pipeline\Pipeline;
use OwenIt\Auditing\Contracts\Auditable;

class Feature extends Model implements TranslatableContract, Auditable
{
    use HasFactory, Translatable, \OwenIt\Auditing\Auditable, ScopeStatus;

    protected $table = 'features';

    protected $fillable = [
        'ranking', 'status'
    ];

    protected $with = ['translations'];

    protected $translationForeignKey = 'feature_id';

    public $translatedAttributes = [
        'name', 'slug'
    ];

    /* Pipeline Query Filter */
    public static function allFeatures($features)
    {
        return app(Pipeline::class)
            ->send($features)
            ->through([
                SortFilter::class,
                NameQueryFilter::class,
                FeatureQueryFilter::class,
            ])
            ->thenReturn()
            ->paginate(Utility::getValByName('pagination_limit'))
            ->withQueryString();
    }

    /* relation */
    public function realEstates(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(RealEstate::class, 'feature_real_estates', 'feature_id', 'real_estate_id')
            ->as('feature_real_estates')
            ->withTimestamps();
    }
}
