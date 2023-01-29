<?php

namespace App\View\Components\Auth\Passwords;

use Illuminate\View\Component;

class Email extends Component
{
    public $routeForgetPassword;
    public $routeLogin;

    public function __construct($routeForgetPassword, $routeLogin)
    {
        $this->routeForgetPassword = $routeForgetPassword;
        $this->routeLogin = $routeLogin;
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Support\Htmlable|string|\Closure|\Illuminate\Contracts\Foundation\Application
    {
        return view('components.auth.passwords.email');
    }
}
