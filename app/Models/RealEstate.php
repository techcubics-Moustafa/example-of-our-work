<?php

namespace App\Models;

use App\Enums\Status;
use App\Helpers\Setting\Utility;
use App\QueryFilters\Api\NameFilter;
use App\QueryFilters\Api\RealEstateFilter;
use App\QueryFilters\Feature\FeatureQueryFilter;
use App\QueryFilters\Name\NameQueryFilter;
use App\QueryFilters\Sort\SortFilter;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pipeline\Pipeline;
use OwenIt\Auditing\Contracts\Auditable;

class RealEstate extends Model implements TranslatableContract, Auditable
{
    use HasFactory, Translatable, \OwenIt\Auditing\Auditable;

    protected $table = 'real_estates';

    protected $fillable = [
        'modelable_type', 'modelable_id', 'user_id', 'publish', 'special_id', 'country_id', 'governorate_id', 'region_id',
        'category_id', 'sub_category_id', 'currency_id', 'location', 'youtube_video_thumbnail', 'youtube_video_url',
        'image', 'start_date', 'end_date'
    ];

    protected $with = ['translations'];

    protected $translationForeignKey = 'real_estate_id';

    public $translatedAttributes = [
        'title', 'slug', 'description', 'content', 'address', 'seo_title', 'seo_description'
    ];

    protected $appends = [
        'lat', 'lng'
    ];

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

    public static function filters($services)
    {
        return app(Pipeline::class)
            ->send($services)
            ->through([
                SortFilter::class,
                NameFilter::class,
                RealEstateFilter::class,
            ])
            ->thenReturn();
    }

    /* scopes */
    public function scopePublish(Builder $builder, $status = Status::Active): Builder
    {
        return $builder->where('publish', '=', $status->value);
    }

    /* relation */

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function modelable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    public function features(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Feature::class, 'feature_real_estates', 'real_estate_id', 'feature_id')
            ->as('feature_real_estates')
            ->withTimestamps();
    }

    public function special(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Special::class, 'special_id');
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

    public function currency(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function likes(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Like::class, 'modelable');
    }

    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Comment::class, 'real_estate_id');
    }

    public function shares(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Share::class, 'real_estate_id');
    }

    /* functions */

    public function likeBy(?User $user): bool
    {
        if (!$user) {
            return false;
        }
        return $this->likes()->where('user_id', '=', $user->id)->exists();
    }
}
