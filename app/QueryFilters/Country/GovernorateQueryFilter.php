<?php

namespace App\QueryFilters\Country;

use App\QueryFilters\Filter;

class GovernorateQueryFilter extends Filter
{
    protected function applyFilter($builder)
    {
        $search = str_replace('Gov#', '', request('search'));

        $builder->when(request()->column_name == 'code', function ($builder) use ($search) {
            $builder->where('id', '=', $search);
        });

        $builder->when(request()->column_name == 'country', function ($builder) use ($search) {
            $builder->whereHas('country', function ($query) use ($search) {
                $query->whereTranslationLike('name', "%{$search}%", default_lang());
            });
        });

        $builder->when(request()->column_name == 'all', function ($builder) use ($search) {
            $builder->orWhere('id', '=', $search)
                ->orWhereHas('country', function ($query) use ($search) {
                    $query->whereTranslationLike('name', "%{$search}%", default_lang());
                });
        });

        return $builder;
    }

}
