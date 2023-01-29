<?php

namespace App\QueryFilters\Sort;

use App\QueryFilters\Filter;

class SortFilter extends Filter
{
    protected function applyFilter($builder)
    {
        if (in_array(request('sort'), ['DESC', 'ASC', 'asc', 'desc'])) {
            $builder->orderBy('id', request('sort'));
        } else {
            $builder->orderByDesc('created_at')->orderBy('id', 'desc');
        }
        return $builder;
    }
}
