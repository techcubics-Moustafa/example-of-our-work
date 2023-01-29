<?php

namespace App\QueryFilters\Name;

use App\QueryFilters\Filter;

class NameQueryFilter extends Filter
{
    protected function applyFilter($builder)
    {
        $search = request()->search;

        $builder->when(request()->column_name == 'name',function ($builder) use ($search){
            $builder->whereTranslationLike('name', "%{$search}%", default_lang());
        });

        $builder->when(request()->column_name == 'all',function ($builder) use ($search){
            $builder->orWhereTranslationLike('name', "%{$search}%", default_lang());
        });

        return $builder;
    }
}
