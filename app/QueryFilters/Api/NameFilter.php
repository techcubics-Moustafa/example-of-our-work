<?php

namespace App\QueryFilters\Api;

use App\QueryFilters\Filter;
use Illuminate\Support\Fluent;

class NameFilter extends Filter
{
    protected function applyFilter($builder)
    {
        $filter = (array)request()->filter;
        $filter = new Fluent($filter);

        $builder->when($filter->name, function ($builder) use ($filter) {
            $builder->whereTranslationLike('name', "%{$filter->name}%", locale());
        });

        $builder->when($filter->title, function ($builder) use ($filter) {
            $builder->whereTranslationLike('title', "%{$filter->title}%", locale());
        });

        $builder->when($filter->description, function ($builder) use ($filter) {
            $builder->whereTranslationLike('description', "%{$filter->description}%", locale());
        });

        $builder->when($filter->content, function ($builder) use ($filter) {
            $builder->whereTranslationLike('content', "%{$filter->content}%", locale());
        });


        return $builder;
    }
}
