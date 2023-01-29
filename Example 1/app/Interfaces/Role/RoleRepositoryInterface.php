<?php

namespace App\Interfaces\Role;

interface RoleRepositoryInterface
{
    public function all();

    public function index($name, $guard_name = 'web');

    public function store($request, $data, $modules, $lists, $guard_name = 'web', $type = 'store');

    public function edit($id, $name, $guard_name = 'web');

    public function update($request, $data, $id, $name, $modules, $lists, $guard_name = 'web', $type = 'store');

    public function destroy($id, $name, $guard_name = 'web');
}
