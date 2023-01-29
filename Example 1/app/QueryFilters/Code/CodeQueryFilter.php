<?php

namespace App\QueryFilters\Code;

use App\QueryFilters\Filter;

class CodeQueryFilter
{
    public $search;

    public function __construct($search)
    {
        $this->search = $search;
    }

    public function code($builder)
    {
        if (request('column_name') == 'code') {
            $builder->where('id', $this->search);
        }
        return $builder;
    }

    public function all($builder)
    {
        if (request('column_name') == 'all') {
            $builder->orWhere('id',$this->search);
        }
        return $builder;
    }
}
