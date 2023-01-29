<?php

namespace App\Models;

use App\Helpers\Setting\Utility;
use App\QueryFilters\Sort\SortFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pipeline\Pipeline;
use OwenIt\Auditing\Contracts\Auditable;

class Project extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $table = 'projects';

    protected $fillable = [
        'company_id', 'status', 'number_blocks', 'number_floors', 'number_flats',
        'min_price', 'max_price', 'open_sell_date', 'finish_date'
    ];

    /* Pipeline Query Filter */
    public static function allProjects($categories)
    {
        return app(Pipeline::class)
            ->send($categories)
            ->through([
                SortFilter::class,
            ])
            ->thenReturn()
            ->paginate(Utility::getValByName('pagination_limit'))
            ->withQueryString();
    }

    /* relations */

    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function properties(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Property::class, 'project_id');
    }

    public function images(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(File::class, 'relationable');
    }

    public function realEstate(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(RealEstate::class, 'modelable');
    }

    public function realEstateDetail(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(RealEstate::class, 'modelable');
    }
}
