<?php

namespace App\Models;

use App\Enums\Status;
use App\Helpers\Setting\Utility;
use App\QueryFilters\Banner\BannerQueryFilter;
use App\QueryFilters\Name\NameQueryFilter;
use App\QueryFilters\Sort\SortFilter;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pipeline\Pipeline;
use OwenIt\Auditing\Contracts\Auditable;

class Banner extends Model implements TranslatableContract, Auditable
{
    use HasFactory, Translatable, \OwenIt\Auditing\Auditable;

    protected $table = 'banners';

    protected $fillable = [
        'link', 'image', 'status','resource_type', 'resource_id','banner_type'
    ];

    protected $with = ['translations'];

    protected $translationForeignKey = 'banner_id';

    public $translatedAttributes = [
        'content',
    ];
    /* Pipeline Query Filter */
    public static function allBanners()
    {

        return app(Pipeline::class)
            ->send(Banner::query())
            ->through([
                SortFilter::class,
                BannerQueryFilter::class,
            ])
            ->thenReturn()
            ->paginate(Utility::getValByName('pagination_limit'))->withQueryString();
    }

    /*scope*/
    public function ScopeStatus($query, $status = Status::Active)
    {
        $query->where('status', $status);
    }
}
