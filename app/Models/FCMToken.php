<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FCMToken extends Model
{
    protected $table = 'f_c_m_tokens';

    protected $fillable = [
        'tokenable_type', 'tokenable_id', 'enable', 'fcm_token', 'device_name', 'lang', 'last_used_at'
    ];

    /* local scope */
    public function scopeEnable($query, $status = 1)
    {
        return $query->where('enable', '=', $status);
    }

    public function tokenable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }
}
