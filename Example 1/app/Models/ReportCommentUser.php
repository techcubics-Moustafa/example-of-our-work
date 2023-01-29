<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportCommentUser extends Model
{
    use HasFactory;

    protected $table = 'report_comment_users';

    protected $fillable = [
        'user_id', 'comment_id', 'report_comment_id'
    ];


    /* relations */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comment(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Comment::class, 'comment_id');
    }

    public function reportComment(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ReportComment::class, 'report_comment_id');
    }
}
