<?php

namespace App\QueryFilters\User;

use App\QueryFilters\Code\CodeQueryFilter;
use App\QueryFilters\Filter;
use Illuminate\Support\Facades\DB;

class UserQueryFilter extends Filter
{
    protected function applyFilter($builder)
    {
        $search = str_replace('User#', '', request('search'));

        if (request()->column_name == 'code') {
            (new CodeQueryFilter($search))->code($builder);
        }

        $builder->when(request()->column_name == 'name', function ($builder) use ($search) {
            $builder->where(DB::raw('CONCAT_WS(" ", first_name, last_name)'), 'LIKE', "%{$search}%");
        });

        $builder->when(request()->column_name == 'phone', function ($builder) use ($search) {
            $builder->where("phone", "LIKE", "%{$search}%");
        });

        $builder->when(request()->column_name == 'email', function ($builder) use ($search) {
            $builder->where("email", "LIKE", "%{$search}%");
        });

        $builder->when(in_array(request()->column_name, ['country', 'governorate', 'region']), function ($builder) use ($search) {
            $relation = request()->column_name;
            $builder->whereHas("{$relation}", function ($query) use ($search) {
                $query->whereTranslationLike('name', "%{$search}%", default_lang());
            });
        });

        $builder->when(request()->column_name == 'all', function ($builder) use ($search) {
            (new CodeQueryFilter($search))->all($builder);
            $builder->orWhere(DB::raw('CONCAT_WS(" ", first_name, last_name)'), 'LIKE', "%{$search}%")
                ->where("phone", "LIKE", "%{$search}%")
                ->where("email", "LIKE", "%{$search}%")
                ->orWhereHas("country", function ($query) use ($search) {
                    $query->orWhereTranslationLike('name', "%{$search}%", default_lang());
                })
                ->orWhereHas("governorate", function ($query) use ($search) {
                    $query->orWhereTranslationLike('name', "%{$search}%", default_lang());
                })
                ->orWhereHas("region", function ($query) use ($search) {
                    $query->orWhereTranslationLike('name', "%{$search}%", default_lang());
                });
        });


        return $builder;
    }
}
