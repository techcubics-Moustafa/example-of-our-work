<?php

namespace App\QueryFilters\Company;

use App\QueryFilters\Code\CodeQueryFilter;
use App\QueryFilters\Filter;
use Illuminate\Support\Facades\DB;

class CompanyQueryFilter extends Filter
{
    protected function applyFilter($builder)
    {
        $search = str_replace('Company#', '', request('search'));

        if (request()->column_name == 'code') {
            (new CodeQueryFilter($search))->code($builder);
        }

        $builder->when(request()->column_name == 'user', function ($builder) use ($search) {
            $builder->whereHas('user', function ($builder) use ($search) {
                $builder->where(DB::raw('CONCAT_WS(" ", first_name, last_name)'), 'LIKE', "%{$search}%");
            });
        });

        $builder->when(in_array(request()->column_name, ['country', 'governorate', 'region']), function ($builder) use ($search) {
            $relation = request()->column_name;
            $builder->whereHas("{$relation}", function ($builder) use ($search) {
                $builder->whereTranslationLike('name', "%{$search}%", default_lang());
            });
        });

        return $builder;
    }
}
