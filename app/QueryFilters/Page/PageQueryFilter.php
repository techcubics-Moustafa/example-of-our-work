<?php

namespace App\QueryFilters\Page;

use App\QueryFilters\Filter;

class PageQueryFilter extends Filter
{
    protected function applyFilter($builder)
    {
        $search = request('search');
        $search = str_replace(' ', '_', $search);

        if (request('column_name') == 'page_type') {

            $builder->where('page_type', 'LIKE', "%{$search}%");
        }

        if (request('column_name') == 'name') {
            $builder->whereTranslationLike('name', "%{$search}%", default_lang());
        }

        if (request('column_name') == 'all') {
            $builder->where('page_type', 'LIKE', "%{$search}%")
                ->orWhereTranslationLike('name', "%{$search}%", default_lang());
        }
        return $builder;
    }
}
