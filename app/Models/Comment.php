<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comments';

    protected $fillable = [
        'user_id', 'real_estate_id', 'parent_id', 'comment'
    ];


    /* relation */

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function realEstate(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(RealEstate::class, 'real_estate_id');
    }

    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function childrenComments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->with('children');
    }

    public static function mainComment(): \Illuminate\Database\Eloquent\Builder
    {
        return self::query()->whereNull('parent_id')->with('childrenComments');
    }

    public function likes(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Like::class, 'modelable');
    }

    public function likeBy(?User $user): bool
    {
        if (!$user) {
            return false;
        }
        return $this->likes()
            ->where('user_id', '=', $user->id)
            ->exists();
    }
}
