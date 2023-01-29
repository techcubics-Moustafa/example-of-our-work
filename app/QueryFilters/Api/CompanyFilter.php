<?php

namespace App\QueryFilters\Api;

use App\QueryFilters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Fluent;

class CompanyFilter extends Filter
{
    protected function applyFilter($builder)
    {
        $filter = (array)request()->filter;
        $filter = new Fluent($filter);

        $builder
            ->when($filter->country, function (Builder $builder) use ($filter) {
                $builder->whereHas("country", function (Builder $builder) use ($filter) {
                    $builder->whereTranslationLike('name', "%{$filter->country}%", locale());
                });
            })
            ->when($filter->governorate, function (Builder $builder) use ($filter) {
                $builder->whereHas("governorate", function (Builder $builder) use ($filter) {
                    $builder->whereTranslationLike('name', "%{$filter->governorate}%", locale());
                });
            })
            ->when($filter->region, function (Builder $builder) use ($filter) {
                $builder->whereHas("region", function (Builder $builder) use ($filter) {
                    $builder->whereTranslationLike('name', "%{$filter->region}%", locale());
                });
            })
            ->when($filter->category, function (Builder $builder) use ($filter) {
                $builder->whereHas("category", function (Builder $builder) use ($filter) {
                    $builder->whereTranslationLike('name', "%{$filter->category}%", locale());
                });
            })
            ->when($filter->sub_category, function (Builder $builder) use ($filter) {
                $builder->whereHas("subCategory", function (Builder $builder) use ($filter) {
                    $builder->whereTranslationLike('name', "%{$filter->sub_category}%", locale());
                });
            })
            ->when($filter->price_from && $filter->price_to, function (Builder $builder) use ($filter) {
                $builder->whereBetween('price', [(float)$filter->price_from, (float)$filter->price_to]);
            })
            ->when($filter->square_from && $filter->square_to, function (Builder $builder) use ($filter) {
                $builder->whereBetween('square', [(float)$filter->square_from, (float)$filter->square_to]);
            });

        return $builder;
    }
}
