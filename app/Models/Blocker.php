<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blocker extends Model
{
    use HasFactory;

    protected $table = 'blockers';

    protected $fillable = [
        'user_id', 'note'
    ];
}
