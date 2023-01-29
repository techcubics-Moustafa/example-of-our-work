<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class BlogTagTranslation extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $table = 'blog_tag_translations';

    protected $fillable = [
        'tag_id', 'name','locale'
    ];
}
