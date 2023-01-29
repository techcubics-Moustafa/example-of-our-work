<?php

namespace App\Models;

use App\Enums\Status;
use App\Helpers\Setting\Utility;
use App\QueryFilters\Country\GovernorateQueryFilter;
use App\QueryFilters\Name\NameQueryFilter;
use App\QueryFilters\Sort\SortFilter;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;

class Governorate extends Model implements TranslatableContract, Auditable
{
    use HasFactory, Translatable, \OwenIt\Auditing\Auditable;

    protected $table = 'governorates';

    protected $fillable = [
        'country_id', 'status'
    ];

    protected $with = ['translations'];

    protected $translationForeignKey = 'governorate_id';

    public $translatedAttributes = [
        'name', 'slug'
    ];

    public static function allGovernorates($governorates)
    {
        return app(Pipeline::class)
            ->send($governorates)
            ->through([
                SortFilter::class,
                NameQueryFilter::class,
                GovernorateQueryFilter::class,
            ])
            ->thenReturn()
            ->paginate(Utility::getValByName('pagination_limit'))->withQueryString();
    }

    public function list(): array
    {
        return array_merge(DB::getSchemaBuilder()->getColumnListing($this->table), $this->translatedAttributes);
    }

    /*scope*/
    public function ScopeStatus($query, $status = Status::Active)
    {
        $query->where('status', $status->value);
    }

    /* relation */

    public function country(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function regions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Region::class, 'governorate_id');
    }

    public function clinics(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Clinic::class, 'governorate_id');
    }

    public function areas(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Areas::class, 'governorate_id');
    }
}
