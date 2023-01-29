<?php

namespace App\Models;

use App\Helpers\Setting\Utility;
use App\QueryFilters\Api\CompanyFilter;
use App\QueryFilters\Api\NameFilter;
use App\QueryFilters\Company\CompanyQueryFilter;
use App\QueryFilters\Name\NameQueryFilter;
use App\QueryFilters\Sort\SortFilter;
use App\Traits\Scopes\ScopeStatus;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pipeline\Pipeline;
use OwenIt\Auditing\Contracts\Auditable;

class Company extends Model implements TranslatableContract, Auditable
{
    use HasFactory, Translatable, \OwenIt\Auditing\Auditable, ScopeStatus;

    protected $table = 'companies';

    protected $fillable = [
        'user_id', 'email', 'phone', 'country_id', 'governorate_id', 'region_id',
        'category_id', 'sub_category_id', 'whatsapp_number', 'logo', 'status',
        'location', 'social_media'
    ];

    protected $with = ['translations'];

    protected $casts = [
        'social_media' => 'array'
    ];

    protected $appends = [
        'lat', 'lng'
    ];

    protected $translationForeignKey = 'company_id';

    public $translatedAttributes = [
        'name', 'slug', 'description', 'address'
    ];

    public static function allCompanies($companies)
    {
        return app(Pipeline::class)
            ->send($companies)
            ->through([
                SortFilter::class,
                NameQueryFilter::class,
                CompanyQueryFilter::class,
            ])
            ->thenReturn()
            ->paginate(Utility::getValByName('pagination_limit'))
            ->withQueryString();
    }

    public static function filters($companies)
    {
        return app(Pipeline::class)
            ->send($companies)
            ->through([
                SortFilter::class,
                NameFilter::class,
                CompanyFilter::class,
            ])
            ->thenReturn();
    }

    public function getLatAttribute(): ?string
    {
        if (!$this->location) return null;
        $location = explode(',', $this->location);
        return $location[0];
    }

    public function getLngAttribute(): ?string
    {
        if (!$this->location) return null;
        $location = explode(',', $this->location);
        return $location[1];
    }

    /* relation */

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function projects(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Project::class, 'company_id');
    }

    public function country(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function governorate(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Governorate::class, 'governorate_id');
    }

    public function region(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function subCategory(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class, 'sub_category_id');
    }
}
