<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PermissionAdmin;
use App\Helpers\PermissionOwner;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function permissionAdmin(): string
    {
        try {
            DB::beginTransaction();
            $roleSuperAdmin = Role::findById(1, 'admin');
            $models = PermissionAdmin::models();
            $lists = PermissionAdmin::lists();
            $adminPermissions = $this->storePermissions($models, $lists, 'admin');
            $roleSuperAdmin->givePermissionTo($adminPermissions);
            DB::commit();
            return redirect()->back()->with('success', 'done');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    private function storePermissions($models, $lists, $guard)
    {
        foreach ($models as $row) {
            foreach ($lists as $value) {
                Permission::query()->updateOrCreate([
                    'name' => $row . " " . $value,
                    'guard_name' => $guard,
                ], [
                    'name' => $row . " " . $value,
                    'guard_name' => $guard,
                ]);
            }
        }
        return $this->permissions($models, $lists);
    }

    private function permissions($modules, $lists): array
    {
        $data = [];
        foreach ($modules as $value) {
            foreach ($lists as $item) {
                $data[] = ['name' => $value . ' ' . $item];
            }
        }
        return $data;
    }

    public function permissionOwner(): string
    {
        try {
            DB::beginTransaction();
            $roleSuperAdmin = Role::findById(2, 'web');
            $models = PermissionOwner::models();
            $lists = PermissionOwner::lists();

            $adminPermissions = $this->storePermissions($models, $lists, 'web');
            $roleSuperAdmin->givePermissionTo($adminPermissions);
            DB::commit();
            return redirect()->back()->with('success', 'Done');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}
