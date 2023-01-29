<?php

namespace App\Models;

use App\Enums\Direction;
use App\Enums\Status;
use App\Helpers\Setting\Utility;
use App\QueryFilters\Language\LanguageQueryFilter;
use App\QueryFilters\Sort\SortFilter;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pipeline\Pipeline;
use OwenIt\Auditing\Contracts\Auditable;

class Language extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $table = 'languages';

    protected $fillable = [
        'name', 'direction', 'code', 'flag', 'status', 'default',
    ];

    public static function allLanguages()
    {
        return app(Pipeline::class)
            ->send(Language::query()->orderByDesc('created_at'))
            ->through([
                SortFilter::class,
                LanguageQueryFilter::class,
            ])
            ->thenReturn()
            ->paginate(Utility::getValByName('pagination_limit'))
            ->withQueryString();
    }


    public function name(): Attribute
    {
        return new Attribute(
            get: fn($value) => ucfirst($value),
            set: fn($value) => ucfirst($value),
        );
    }

    /* scope */
    public function scopeStatus($query, $status = Status::Active)
    {
        return $query->where('status', $status->value);
    }

    public function scopeDefault($query, $status = Status::Active)
    {
        return $query->where('default', $status);
    }
}
