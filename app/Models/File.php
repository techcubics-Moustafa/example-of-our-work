<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $table = 'files';

    protected $fillable = [
        'name', 'size', 'file', 'path', 'full_file', 'mime_type', 'relationable_id', 'relationable_type'
    ];

    /* scope */
    public function ScopeModel($query, $modelId, $model)
    {
        $query->where([
            ['relationable_id', '=', $modelId],
            ['relationable_type', '=', $model],
        ]);
    }

    /* relation */
    public function relationable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }


}
