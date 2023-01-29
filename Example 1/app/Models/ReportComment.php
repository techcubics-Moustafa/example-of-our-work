<?php

namespace App\Models;

use App\Enums\Status;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportComment extends Model implements TranslatableContract
{
    use HasFactory, Translatable;

    protected $table = 'report_comments';

    protected $fillable = [
        'ranking', 'status'
    ];

    protected $with = ['translations'];

    protected $translationForeignKey = 'report_comment_id';

    public $translatedAttributes = [
        'title',
    ];

    /* scope */
    public function scopeStatus($query, $status = Status::Active)
    {
        return $query->where('status', $status->value);
    }

    /* relations */

    public function reportCommentUsers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ReportCommentUser::class, 'report_comment_id');
    }
}
