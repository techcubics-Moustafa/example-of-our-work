<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Language extends Component
{
    public function __construct(public $guard)
    {

    }
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Support\Htmlable|string|\Closure|\Illuminate\Contracts\Foundation\Application
    {
        $languages = languages();
        return view('components.language', compact('languages'));
    }
}
