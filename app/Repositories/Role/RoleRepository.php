<?php

namespace App\Repositories\Role;

use App\Interfaces\Role\RoleRepositoryInterface;
use Spatie\Permission\Models\Permission;
use App\Models\Role;

class RoleRepository implements RoleRepositoryInterface
{
    public Role $model;

    public function __construct(Role $model)
    {
        $this->model = $model;
    }

    public function all(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->model->query();
    }

    public function index($name = null, $guard_name = null): \Illuminate\Database\Eloquent\Builder
    {
        return $this->model->query()->withCount(['permissions','users'])
            ->where([
                ['guard_name', '=', $guard_name],
                ['name', '!=', $name],
            ]);
    }

    public function store($request, $data, $modules, $lists, $guard_name = 'web', $type = 'store'): void
    {
        $role = $this->model->query()->create($data);
        if ($role) {
            $this->storeDataRole($modules, $lists, $request, $role, $guard_name, $type);
        }
    }

    public function edit($id, $name, $guard_name = 'web'): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder|array|null
    {
        return $this->model->query()
            ->where([
                ['guard_name', '=', $guard_name],
                ['name', '!=', $name],
            ])->findOrFail($id);
    }

    public function update($request, $data, $id, $name, $modules, $lists, $guard_name = 'web', $type = 'store'): void
    {
        $role = $this->model->query()
            ->where([
                ['guard_name', '=', $guard_name],
                ['name', '!=', $name],
            ])->findOrFail($id);
        if ($role->update($data)) {
            $all_permissions = $this->storeDataRole($modules, $lists, $request, $role, $guard_name, 'update');
            $role->syncPermissions($all_permissions);
        }
    }

    public function destroy($id, $name, $guard_name = 'web'): void
    {
        $role = $this->model->query()
            ->where([
                ['guard_name', '=', $guard_name],
                ['name', '!=', $name],
            ])->findOrFail($id);
        foreach ($role->getAllPermissions() as $permission) {
            $role->revokePermissionTo($permission);
            $permission->removeRole($role);
        }
        $role->delete();
    }

    private function storeDataRole($modules, $lists, $request, $role, $guard_name, $type = 'store'): array
    {
        $all_permissions = [];
        foreach ($modules as $row) {
            foreach ($lists as $list) {
                $permission = $row . "_" . $list;
                if (!$request->$permission) continue;
                if ($type == 'store') {
                    $this->findPermission($permission, $role, guard_name: $guard_name);
                } else {
                    $all_permissions[] = $this->findPermission($permission, $role, $type, guard_name: $guard_name);
                }
            }
        }
        return $all_permissions;
    }

    private function findPermission($value, $role, $type = 'store', $guard_name = 'web'): \Spatie\Permission\Contracts\Permission
    {
        $name = str_replace("_", " ", $value);
        $permission = Permission::findByName($name, $guard_name);
        if ($type == 'store') {
            $role->givePermissionTo($permission);
            return $permission;
        }
        $permission->assignRole($role);
        return $permission;
    }
}
