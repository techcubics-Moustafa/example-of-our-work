<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class BannerTranslation extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $table = 'banner_translations';

    protected $fillable = [
        'banner_id', 'content', 'locale'
    ];
}
