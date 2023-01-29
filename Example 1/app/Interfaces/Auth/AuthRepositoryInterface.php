<?php

namespace App\Interfaces\Auth;

interface AuthRepositoryInterface
{
    public function changePassword($request, $user);

    public function logout($guard);
}
