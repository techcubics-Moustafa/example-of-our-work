<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Login extends Component
{
    public $routeLogin;
    public $resetPassword;

    public function __construct($routeLogin,$resetPassword)
    {
        $this->routeLogin = $routeLogin;
        $this->resetPassword = $resetPassword;
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Support\Htmlable|string|\Closure|\Illuminate\Contracts\Foundation\Application
    {
        return view('components.auth.login');
    }
}
