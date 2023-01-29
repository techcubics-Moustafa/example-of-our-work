<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Search extends Component
{
    public $columns;

    public function __construct($columns)
    {
        $this->columns = $columns;
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Support\Htmlable|string|\Closure|\Illuminate\Contracts\Foundation\Application
    {
        return view('components.search');
    }
}
