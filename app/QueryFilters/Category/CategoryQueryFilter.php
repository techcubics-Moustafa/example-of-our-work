<?php

namespace App\QueryFilters\Category;

use App\QueryFilters\Code\CodeQueryFilter;
use App\QueryFilters\Filter;

class CategoryQueryFilter extends Filter
{
    protected function applyFilter($builder)
    {
        $search = str_replace('Category#', '', request('search'));
        if (request('column_name') == 'code') {
            (new CodeQueryFilter($search))->code($builder);        }

        if (request('column_name') == 'ranking' && request()->filled('column_name')) {
            $builder->where('ranking','=',$search);
        }
        if (request('column_name') == 'all' && request()->filled('column_name')) {
            (new CodeQueryFilter($search))->all($builder);
            $builder->orWhere('ranking','=', $search);
        }
        return $builder;
    }
}
