<?php

namespace App\QueryFilters\Country;

use App\QueryFilters\Filter;

class CountryQueryFilter extends Filter
{
    protected function applyFilter($builder)
    {
        $search = str_replace('Country#', '', request('search'));

        if (request('column_name') == 'code') {
            $builder->where('id', '=', $search);
        }
        if (request('column_name') == 'all') {
            $builder->orWhere('id', 'LIKE', $search);
        }
        return $builder;
    }

}
