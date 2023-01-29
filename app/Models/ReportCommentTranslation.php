<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportCommentTranslation extends Model
{
    use HasFactory;

    protected $table = 'report_comment_translations';

    protected $fillable = [
        'report_comment_id', 'title', 'locale'
    ];

    public $timestamps = false;

    public function title(): Attribute
    {
        return new Attribute(
            get: fn($value) => ucfirst($value),
            set: fn($value) => ucfirst($value),
        );
    }
}
