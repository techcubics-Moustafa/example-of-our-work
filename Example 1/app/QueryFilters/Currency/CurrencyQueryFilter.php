<?php

namespace App\QueryFilters\Currency;

use App\QueryFilters\Filter;

class CurrencyQueryFilter extends Filter
{
    protected function applyFilter($builder)
    {
        $search = str_replace('Currency#', '', request('search'));

        if (request('column_name') == 'code') {
            $builder->where('id', $search);
        }

        if (request('column_name') == 'all') {
            $builder->orWhere('id', '=', $search);
        }
        return $builder;
    }
}
