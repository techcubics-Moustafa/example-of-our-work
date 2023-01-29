<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $table = 'answers';

    protected $fillable = [
        'question_id', 'modelable_type', 'modelable_id', 'answer'
    ];


    public function modelable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    public function question(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
}
