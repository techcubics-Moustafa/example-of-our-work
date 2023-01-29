<?php

namespace App\QueryFilters\Feature;

use App\QueryFilters\Code\CodeQueryFilter;
use App\QueryFilters\Filter;

class FeatureQueryFilter extends Filter
{
    protected function applyFilter($builder)
    {
        if (request()->routeIs('admin.feature.index')) {
            $search = str_replace('Feature#', '', request('search'));
        } else {
            $search = str_replace('Service#', '', request('search'));
        }

        if (request()->column_name == 'code') {
            (new CodeQueryFilter($search))->code($builder);
        }

        if (request()->column_name == 'ranking') {
            $builder->where('ranking', '=', $search);
        }
        if (request()->column_name == 'all') {
            (new CodeQueryFilter($search))->all($builder);
            $builder->orWhere('ranking', '=', $search);
        }
        return $builder;
    }
}
