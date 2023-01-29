<?php

namespace App\QueryFilters\Admin;

use App\QueryFilters\Filter;

class AdminQueryFilter extends Filter
{
    protected function applyFilter($builder)
    {
        $search = str_replace('Employee#', '', request('search'));

        if (request('column_name') == 'code') {
            $builder->where('id', $search);
        }

        if (request('column_name') == 'name') {
            $builder->where('name', 'LIKE', "%{$search}%");
        }

        if (request('column_name') == 'email') {
            $builder->where('email', 'LIKE', "%{$search}%");;
        }

        if (request('column_name') == 'phone') {
            $builder->where('phone', 'LIKE', "%{$search}%");;
        }

        if (request('column_name') == 'role_name') {
            $builder->whereHas('role', function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")->whereNull('created_by')->whereNotIn('id', [1, 2]);
            });
        }

        if (request('column_name') == 'all') {
            $builder->where(function ($query) use ($search) {
                $query->where('id', "LIKE", "%{$search}%")
                    ->orWhere('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%");
            })->orWhereHas('role', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                        ->whereNull('created_by')->whereNotIn('id', [1, 2]);
                });
        }
        return $builder;
    }
}
