<?php

namespace App\QueryFilters\Driver;

use App\QueryFilters\Code\CodeQueryFilter;
use App\QueryFilters\Filter;

class DriverQueryFilter extends Filter
{
    protected function applyFilter($builder)
    {
        $search = str_replace('Driver#', '', request('search'));

        if (request()->column_name == 'code') {
            (new CodeQueryFilter($search))->code($builder);
        }

        $builder->when(request()->column_name == 'name', function ($builder) use ($search) {
            $builder->whereRelation("user", "name", "LIKE", "%{$search}%");
        });

        $builder->when(request()->column_name == 'phone', function ($builder) use ($search) {
            $builder->where("phone", "LIKE", "%{$search}%");
        });

        $builder->when(request()->column_name == 'national_id', function ($builder) use ($search) {
            $builder->where("national_id", "LIKE", "%{$search}%");
        });

        $builder->when(request('guard') == 'admin', function ($builder) use ($search) {
            $builder
                ->whereHas('owner.user', function ($builder) use ($search) {
                    $builder->where("name", "LIKE", "%{$search}%");
                })
                ->whereHas('company', function ($builder) use ($search) {
                    $builder->whereTranslationLike('name', "%{$search}%", default_lang());
                });
        });

        $builder->when(auth()->user()->user_type == 'owner', function ($builder) use ($search) {
            $builder->whereHas('company', function ($builder) use ($search) {
                $builder->whereTranslationLike('name', "%{$search}%", default_lang());
            });
        });

        $builder->when(request()->column_name == 'all', function ($builder) use ($search) {
            (new CodeQueryFilter($search))->all($builder);
            $builder->orWhere('national_id', 'LIKE', "%{$search}%")
                ->orWhere('phone', 'LIKE', "%{$search}%")
                ->orWhereRelation("user", "name", "LIKE", "%{$search}%")
                ->when(auth()->user()->user_type == 'owner', function ($builder) use ($search) {
                    $builder->orwhereHas('company', function ($builder) use ($search) {
                        $builder->orWhereTranslationLike('name', "%{$search}%", default_lang());
                    });
                })
                ->when(request('guard') == 'admin', function ($builder) use ($search) {
                    $builder
                        ->orWhereHas('owner.user', function ($builder) use ($search) {
                            $builder->orWhere("name", "LIKE", "%{$search}%");
                        })
                        ->orWhereHas('company', function ($builder) use ($search) {
                            $builder->orwhereTranslationLike('name', "%{$search}%", default_lang());
                        });
                });
        });

        return $builder;
    }
}
