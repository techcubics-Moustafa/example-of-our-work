<?php

namespace App\Models;

use App\Enums\Status;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class BlogTag extends Model implements TranslatableContract, Auditable
{
    use HasFactory, Translatable, \OwenIt\Auditing\Auditable;

    protected $table = 'blog_tags';

    protected $fillable = [
         'blog_id', 'status'
    ];

    protected $with = ['translations'];

    protected $translationForeignKey = 'tag_id';

    public $translatedAttributes = [
        'name'
    ];

    /*scope*/
    public function ScopeStatus($query, $status = Status::Active)
    {
        $query->where('status', $status);
    }

    public function blogs(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Blog::class, 'blog_id');
    }
}
