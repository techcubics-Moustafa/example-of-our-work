<?php

namespace App\QueryFilters\Language;

use App\QueryFilters\Filter;

class LanguageQueryFilter extends Filter
{
    protected function applyFilter($builder)
    {
        $search = request('search');

        if (request('column_name') == 'code') {
            $builder->where('code', 'LIKE', "%{$search}%");
        }

        if (request('column_name') == 'name') {
            $builder->where('name', 'LIKE', "%{$search}%");
        }

        if (request('column_name') == 'all') {
            $builder->where(function ($q) use ($search) {
                $q->where('code', 'LIKE', "%{$search}%")
                    ->orWhere('name', 'LIKE', "%{$search}%");
            });
        }
        return $builder;
    }
}
