<?php

namespace App\Models;

use App\Helpers\Setting\Utility;
use App\QueryFilters\Api\PropertyFilter;
use App\QueryFilters\Sort\SortFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pipeline\Pipeline;
use OwenIt\Auditing\Contracts\Auditable;

class Property extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $table = 'properties';

    protected $fillable = [
        'type', 'status', 'moderation_status', 'project_id', 'number_bedrooms',
        'number_bathrooms', 'number_floors', 'square', 'price'
    ];


    /* Pipeline Query Filter */
    public static function allProperties($properties)
    {
        return app(Pipeline::class)
            ->send($properties)
            ->through([
                SortFilter::class,
            ])
            ->thenReturn()
            ->paginate(Utility::getValByName('pagination_limit'))
            ->withQueryString();
    }

    public static function filters($properties)
    {
        return app(Pipeline::class)
            ->send($properties)
            ->through([
                SortFilter::class,
                PropertyFilter::class,
            ])
            ->thenReturn();
    }

    /* relations */

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

    public function project(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
