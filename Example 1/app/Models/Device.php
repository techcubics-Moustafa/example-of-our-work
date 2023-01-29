<?php

namespace App\Models;

use App\Helpers\Setting\Utility;
use App\QueryFilters\Setting\DeviceQueryFilter;
use App\QueryFilters\Sort\SortFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pipeline\Pipeline;

class Device extends Model
{
    use HasFactory;

    protected $table = 'devices';

    protected $fillable = [
        'customer_id', 'platform', 'browser', 'browser_version',
    ];

    public static function allDevices($query)
    {
        return app(Pipeline::class)
            ->send($query)
            ->through([
                SortFilter::class,
               DeviceQueryFilter::class,
            ])
            ->thenReturn()
            ->paginate(Utility::getValByName('pagination_limit'));
    }

    /* relation */
    public function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Customer::class,'customer_id');
    }
}
