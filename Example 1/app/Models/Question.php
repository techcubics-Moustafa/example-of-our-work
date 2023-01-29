<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $table = 'questions';

    protected $fillable = [
        'user_id', 'country_id', 'governorate_id', 'region_id', 'question'
    ];


    /* relations */

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function country(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function governorate(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Governorate::class, 'governorate_id');
    }

    public function region(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id');
    }
    public function answers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Answer::class, 'question_id');
    }

    public function answer(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Answer::class, 'question_id')->latestOfMany();
    }
}
