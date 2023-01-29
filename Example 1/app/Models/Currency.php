<?php

namespace App\Models;

use App\Enums\Status;
use App\Helpers\Setting\Utility;
use App\QueryFilters\Currency\CurrencyQueryFilter;
use App\QueryFilters\Name\NameQueryFilter;
use App\QueryFilters\Sort\SortFilter;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pipeline\Pipeline;
use OwenIt\Auditing\Contracts\Auditable;

class Currency extends Model implements TranslatableContract, Auditable
{
    use HasFactory, Translatable, \OwenIt\Auditing\Auditable;

    protected $table = 'currencies';

    protected $fillable = [
        'code', 'status',
    ];

    protected $with = ['translations'];

    protected $translationForeignKey = 'currency_id';

    public $translatedAttributes = [
        'name', 'slug'
    ];

    public static function allCurrencies()
    {
        return app(Pipeline::class)
            ->send(Currency::query()->orderByDesc('created_at'))
            ->through([
                SortFilter::class,
                NameQueryFilter::class,
                CurrencyQueryFilter::class,
            ])
            ->thenReturn()
            ->paginate(Utility::getValByName('pagination_limit'))->withQueryString();
    }

    /*scope*/
    public function ScopeStatus($query, $status = Status::Active)
    {
        $query->where('status', $status->value);
    }
}
