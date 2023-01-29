<?php

namespace App\Traits\Scopes;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Builder;

trait ScopeStatus
{

    public function scopeStatus(Builder $builder, $status = Status::Active): Builder
    {
        return $builder->where('status', '=', $status->value);
    }
}
