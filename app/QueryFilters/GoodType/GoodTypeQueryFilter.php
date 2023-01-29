<?php

namespace App\QueryFilters\GoodType;

use App\QueryFilters\Filter;

class GoodTypeQueryFilter extends Filter
{
    protected function applyFilter($builder)
    {
        $search = request('search');

        $builder->when(request()->column_name, function ($builder) use ($search) {
            $builder->orWhere('id', str_replace('GT#', '', $search));
        });

        return $builder;
    }

}
