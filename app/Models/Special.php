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

class Special extends Model implements TranslatableContract, Auditable
{
    use HasFactory, Translatable, \OwenIt\Auditing\Auditable, ScopeStatus;

    protected $table = 'specials';

    protected $fillable = [
        'ranking', 'status'
    ];

    protected $with = ['translations'];

    protected $translationForeignKey = 'special_id';

    public $translatedAttributes = [
        'name', 'slug'
    ];

    /* Pipeline Query Filter */
    public static function allSpecials($specials)
    {
        return app(Pipeline::class)
            ->send($specials)
            ->through([
                SortFilter::class,
                NameQueryFilter::class,
                FeatureQueryFilter::class,
            ])
            ->thenReturn()
            ->paginate(Utility::getValByName('pagination_limit'))
            ->withQueryString();
    }


    /* relations */

    public function realEstates(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RealEstate::class, 'special_id');
    }

}
