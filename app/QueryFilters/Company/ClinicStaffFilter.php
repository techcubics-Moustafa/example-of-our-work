<?php

namespace App\QueryFilters\Company;

use App\QueryFilters\Filter;

class ClinicStaffFilter extends Filter
{
    protected function applyFilter($builder)
    {
        $search = str_replace('Staff#', '', request('search'));

        if (request('column_name') == 'name') {
            $builder->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%");
            });
        }
        if (request('column_name') == 'phone') {
            $builder->where('phone', 'LIKE', "%{$search}%");
        }
        if (request('column_name') == 'owner_name') {
            $builder->whereHas('owner.user', function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%");
            });
        }
        if (request('column_name') == 'code') {
            $builder->where('id', $search);
        }
        if (request('column_name') == 'job_number') {
            $builder->where('job_number', $search);
        }
        if (request('column_name') == 'national_number') {
            $builder->where('national_number', $search);
        }
        if (request('column_name') == 'brand_name') {
            $builder->whereHas('owner', function ($q) use ($search) {
                $q->where('brand_name', 'LIKE', "%{$search}%");
            });
        }
        if (request('column_name') == 'clinic') {
            $builder->whereHas('clinic', function ($q) use ($search) {
                $q->whereTranslationLike('name', "%{$search}%", default_lang());
            });
        }

        if (request('column_name') == 'all') {
            $builder->orWhere('id', "LIKE", "%{$search}%")
                ->orWhereHas('user', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%");
                })->orWhereHas('clinic', function ($q) use ($search) {
                    $q->whereTranslationLike('name', "%{$search}%", default_lang());
                })
                ->orWhereHas('owner.user', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%");
                })
                ->orWhere('job_number', $search)
                ->orWhere('national_number', $search)
                ->orWhereHas('owner', function ($q) use ($search) {
                    $q->where('brand_name', 'LIKE', "%{$search}%");
                });
        }
        return $builder;
    }
}
