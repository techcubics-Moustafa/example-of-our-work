<?php

namespace App\Models;

use App\Enums\Status;
use App\Helpers\Setting\Utility;
use App\QueryFilters\Country\CountryQueryFilter;
use App\QueryFilters\Name\NameQueryFilter;
use App\QueryFilters\Sort\SortFilter;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pipeline\Pipeline;
use OwenIt\Auditing\Contracts\Auditable;

class Country extends Model implements TranslatableContract, Auditable
{
    use HasFactory, Translatable, \OwenIt\Auditing\Auditable;

    protected $table = 'countries';

    protected $fillable = [
        'code', 'icon', 'status', 'currency_id'
    ];

    protected $with = ['translations'];

    protected $translationForeignKey = 'country_id';

    public $translatedAttributes = [
        'name', 'slug', 'nationality'
    ];

    /* Pipeline Query Filter */
    public static function allCountries($countries)
    {
        return app(Pipeline::class)
            ->send($countries)
            ->through([
                SortFilter::class,
                NameQueryFilter::class,
                CountryQueryFilter::class,
            ])
            ->thenReturn()
            ->paginate(Utility::getValByName('pagination_limit'))->withQueryString();
    }

    /*scope*/
    public function ScopeStatus($query, $status = Status::Active)
    {
        $query->where('status', $status->value);
    }

    /* relation */

    public function governorates(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Governorate::class, 'country_id');
    }

    public function regions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Region::class, 'country_id');
    }

    public function currency(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }
}
