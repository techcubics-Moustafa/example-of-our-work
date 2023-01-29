<?php

namespace App\Models;

use App\Enums\Status;
use App\Helpers\Setting\Utility;
use App\QueryFilters\Name\NameQueryFilter;
use App\QueryFilters\Page\PageQueryFilter;
use App\QueryFilters\Clinic\ClinicQueryFilter;
use App\QueryFilters\Sort\SortFilter;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pipeline\Pipeline;
use OwenIt\Auditing\Contracts\Auditable;

class Page extends Model implements TranslatableContract, Auditable
{
    use HasFactory, Translatable, \OwenIt\Auditing\Auditable;

    protected $table = 'pages';

    protected $fillable = [
        'page_type', 'image', 'status'
    ];

    protected $with = ['translations'];

    protected $translationForeignKey = 'page_id';

    public $translatedAttributes = [
        'name', 'slug', 'description',
    ];

    public static function allPages()
    {
        return app(Pipeline::class)
            ->send(Page::query()->orderByDesc('created_at'))
            ->through([
                SortFilter::class,
                PageQueryFilter::class,
            ])
            ->thenReturn()
            ->paginate(Utility::getValByName('pagination_limit'))->withQueryString();
    }

    /* scope */
    public function scopeStatus($query, $status = Status::Active)
    {
        return $query->where('status', $status);
    }
}
