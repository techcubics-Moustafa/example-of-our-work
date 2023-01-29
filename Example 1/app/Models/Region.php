<?php

namespace App\Models;

use App\Enums\Status;
use App\Helpers\Setting\Utility;
use App\QueryFilters\Country\RegionQueryFilter;
use App\QueryFilters\Name\NameQueryFilter;
use App\QueryFilters\Sort\SortFilter;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pipeline\Pipeline;
use OwenIt\Auditing\Contracts\Auditable;

class Region extends Model implements TranslatableContract, Auditable
{
    use HasFactory, Translatable, \OwenIt\Auditing\Auditable;

    protected $table = 'regions';

    protected $fillable = [
        'country_id', 'governorate_id', 'status'
    ];

    protected $with = ['translations'];

    protected $translationForeignKey = 'region_id';

    public $translatedAttributes = [
        'name', 'slug'
    ];

    public static function allRegions($regions)
    {
        return app(Pipeline::class)
            ->send($regions)
            ->through([
                SortFilter::class,
                NameQueryFilter::class,
                RegionQueryFilter::class,
            ])
            ->thenReturn()
            ->paginate(Utility::getValByName('pagination_limit'))->withQueryString();
    }


    /*scope*/
    public function ScopeStatus($query, $status = Status::Active)
    {
        $query->where('status', $status->value);
    }

    /* relations */

    public function country(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function governorate(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Governorate::class, 'governorate_id');
    }

    public function clinics(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Clinic::class, 'region_id');
    }

    public function areas(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Areas::class, 'area_regions', 'region_id', 'area_id')
            ->as('area_regions')->withTimestamps();
    }


}
