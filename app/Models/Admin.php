<?php

namespace App\Models;

use App\Enums\Status;
use App\Helpers\Setting\Utility;
use App\QueryFilters\Admin\AdminQueryFilter;
use App\QueryFilters\Sort\SortFilter;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use HasRoles, HasFactory, Notifiable;

    protected $table = 'admins';

    protected $guard_name = 'admin';

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'lang', 'status', 'avatar', 'role_id'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function allAdmin($query)
    {
        return app(Pipeline::class)
            ->send($query)
            ->through([
                SortFilter::class,
                AdminQueryFilter::class,
            ])
            ->thenReturn()
            ->paginate(Utility::getValByName('pagination_limit'))
            ->withQueryString();
    }

    public function password(): Attribute
    {
        return new Attribute(
            set: fn($value) => Hash::make($value),
        );
    }

    public function name(): Attribute
    {
        return new Attribute(
            get: fn($value) => ucfirst($value),
            set: fn($value) => ucfirst($value),
        );
    }

    /* scope */
    public function scopeStatus($query, $status = Status::Active)
    {
        return $query->where('status', $status->value);
    }

    /* relation */

    public function role(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

}
