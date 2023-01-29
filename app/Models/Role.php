<?php

namespace App\Models;

use App\Helpers\Setting\Utility;
use App\QueryFilters\Role\RoleQueryFilter;
use App\QueryFilters\Sort\SortFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Pipeline\Pipeline;

class Role extends \Spatie\Permission\Models\Role
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = ['name', 'guard_name', 'created_by'];

    //protected $with = ['createdBy'];

    public static function allRoles($query)
    {
        return app(Pipeline::class)
            ->send($query)
            ->through([
                SortFilter::class,
                RoleQueryFilter::class,
            ])
            ->thenReturn()
            ->paginate(Utility::getValByName('pagination_limit'))->withQueryString();
    }

    /* relation*/
    public function createdBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
