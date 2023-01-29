<?php

namespace App\QueryFilters\RealEstate;

use App\QueryFilters\Filter;
use Illuminate\Support\Fluent;

class RealEstateFilter extends Filter
{
    protected function applyFilter($builder)
    {
        $filter = (array)request()->filter;
        $filter = new Fluent($filter);

        $builder
            ->when($filter->country_id, function ($builder) use ($filter) {
                $builder->where('country_id', '=', $filter->country_id);
            })
            ->when($filter->governorate_id, function ($builder) use ($filter) {
                $builder->where('governorate_id', '=', $filter->governorate_id);
            })
            ->when($filter->region_id, function ($builder) use ($filter) {
                $builder->where('governorate_id', '=', $filter->region_id);
            })
            ->when($filter->category_id, function ($builder) use ($filter) {
                $builder->where('category_id', '=', $filter->category_id);
            })
            ->when($filter->code, function ($builder) use ($filter) {
                $builder->where('id', '=', $filter->code);
            })
            ->when($filter->sub_category_id, function ($builder) use ($filter) {
                $builder->where('sub_category_id', '=', $filter->sub_category_id);
            });



        return $builder;

    }
}
