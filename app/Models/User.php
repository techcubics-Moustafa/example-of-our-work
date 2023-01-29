<?php

namespace App\Models;

use App\Enums\Status;
use App\Enums\UserType;
use App\Helpers\Setting\Utility;
use App\QueryFilters\User\UserQueryFilter;
use App\QueryFilters\Sort\SortFilter;
use App\Traits\Scopes\ScopeStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, ScopeStatus;

    protected $table = 'users';

    protected $guard_name = 'web';

    protected $fillable = [
        'user_type', 'first_name', 'last_name', 'email', 'phone', 'password', 'lang', 'status', 'blocked',
        'avatar', 'country_id', 'governorate_id', 'region_id', 'address', 'gender', 'role_id',
        'provider_type', 'provider_id'
    ];

    protected $appends = [
        'name'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public static function allUsers($users)
    {
        return app(Pipeline::class)
            ->send($users)
            ->through([
                SortFilter::class,
                UserQueryFilter::class,
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


    public function firstName(): Attribute
    {
        return new Attribute(
            get: fn($value) => ucfirst($value),
            set: fn($value) => ucfirst($value),
        );
    }

    public function lastName(): Attribute
    {
        return new Attribute(
            get: fn($value) => ucfirst($value),
            set: fn($value) => ucfirst($value),
        );
    }

    public function getNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public static function findByEmailOrPhone($username)
    {
        return static::query()->where(function ($row) use ($username) {
            $row->where('email', $username)->orWhere('phone', $username);
        })->first();
    }


    public function scopeBlocked(Builder $builder, $status = Status::Not_Active): Builder
    {
        return $builder->where('blocked', '=', $status->value);
    }

    public function scopeUserType(Builder $builder, $userType = UserType::Individual): Builder
    {
        return $builder->where('user_type', '=', $userType->value);
    }

    /* relation */

    public function company(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Company::class, 'user_id');
    }

    public function country(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function governorate(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Governorate::class, 'governorate_id');
    }

    public function region(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function role(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function fcmTokens(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(FCMToken::class, 'tokenable');
    }

    public function blockers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Blocker::class, 'user_id');
    }

    public function verificationCode(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(VerificationCode::class, 'modelable');
    }

    public function services(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Service::class, 'user_id');
    }

    public function questions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Question::class, 'user_id');

    }

    public function likes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Like::class, 'user_id');
    }

    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Comment::class, 'user_id');
    }

    public function shares(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Share::class, 'user_id');
    }

    public function realEstates(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RealEstate::class, 'user_id');
    }

}
