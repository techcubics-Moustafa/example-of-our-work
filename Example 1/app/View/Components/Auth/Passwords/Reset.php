<?php

namespace App\View\Components\Auth\Passwords;

use Illuminate\View\Component;

class Reset extends Component
{
    public $routeReset;
    public $reset;

    public function __construct($reset, $routeReset)
    {
        $this->reset = $reset;
        $this->routeReset = $routeReset;
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Support\Htmlable|string|\Closure|\Illuminate\Contracts\Foundation\Application
    {
        return view('components.auth.passwords.reset');
    }
}
