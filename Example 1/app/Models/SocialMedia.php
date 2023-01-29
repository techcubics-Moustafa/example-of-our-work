<?php

namespace App\Models;

use App\Traits\Scopes\ScopeStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class SocialMedia extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable, ScopeStatus;

    protected $table = 'social_media';

    protected $fillable = [
        'slug', 'icon', 'url', 'status'
    ];
}
