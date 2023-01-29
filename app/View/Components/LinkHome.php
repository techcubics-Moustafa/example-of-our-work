<?php

namespace App\View\Components;

use Illuminate\View\Component;

class LinkHome extends Component
{
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Support\Htmlable|string|\Closure|\Illuminate\Contracts\Foundation\Application
    {
        $route = route('admin.dashboard') ;
        return view('components.link-home',compact('route'));
    }
}
