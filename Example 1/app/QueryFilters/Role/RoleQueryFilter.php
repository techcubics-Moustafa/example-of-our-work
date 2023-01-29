<?php

namespace App\QueryFilters\Role;

use App\QueryFilters\Filter;

class RoleQueryFilter extends Filter
{
    protected function applyFilter($builder)
    {
        $search = request()->search;

        $builder->when(request()->search || request()->name, function ($query) use ($search) {
            if (request()->filled('search')) {
                $name = $search;
            } else {
                $name = request()->name;
            }
            $query->where('name', 'LIKE', "%{$name}%");
        });

        /*if ($user->user_type == UserType::Super_Admin->value || $user->user_type == UserType::Admin->value) {
            if (request('column_name') == 'owner_name') {
                $builder->whereHas('createdBy', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%");
                });
            }
        }*/
        return $builder;
    }
}
