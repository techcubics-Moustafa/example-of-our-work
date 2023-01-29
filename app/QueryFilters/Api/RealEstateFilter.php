<?php

namespace App\QueryFilters\Api;

use App\QueryFilters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Fluent;

class RealEstateFilter extends Filter
{
    protected function applyFilter($builder)
    {
        $filter = (array)request()->filter;
        $filter = new Fluent($filter);

        $builder->when($filter->special, function (Builder $builder) use ($filter) {
            $builder->where('special_id', '=', $filter->special);
        });
        return $builder;
    }
}
