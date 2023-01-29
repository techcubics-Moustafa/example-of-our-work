<?php

namespace App\Models;

use App\Events\UpdateSetting;
use App\Helpers\Setting\Utility;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Setting extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $table = 'settings';

    protected $fillable = [
        'key', 'value'
    ];

    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();
        static::saved(function ($model) {
            event(new UpdateSetting());
        });
        static::updated(function ($model) {
            event(new UpdateSetting());
        });
    }

    public static function getValByName($key): string
    {
        $setting = Utility::settings();
        if (empty($setting[$key])) {
            $setting[$key] = '';
        }

        return $setting[$key];
    }

}
