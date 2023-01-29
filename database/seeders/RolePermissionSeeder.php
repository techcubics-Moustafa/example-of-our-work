<?php

namespace Database\Seeders;

use App\Helpers\PermissionAdmin;
use App\Helpers\PermissionOwner;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{

    public function run()
    {
        Artisan::call('storage:link');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table(App::make(User::class)->getTable())->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $tableNames = config('permission.table_names');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table($tableNames['role_has_permissions'])->truncate();
        DB::table($tableNames['model_has_roles'])->truncate();
        DB::table($tableNames['model_has_permissions'])->truncate();
        DB::table($tableNames['roles'])->truncate();
        DB::table($tableNames['permissions'])->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        app('cache')
            ->store(config('permission.cache.store') != 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));

        // Super admin
        $roleSuperAdmin = Role::query()->updateOrCreate([
            'name' => 'super_admin',
            'guard_name' => 'admin'
        ], [
            'name' => 'super_admin',
            'guard_name' => 'admin'
        ]);

        $superAdmin = Admin::query()->updateOrCreate([
            'email' => 'admin@admin.com',
        ], [
            'name' => 'Super Admin',
            'email' => 'admin@admin.com',
            'phone' => '01111111111',
            'password' => '123456789',
            'lang' => 'en',
            'role_id' => $roleSuperAdmin->id,

        ]);
        $admin = new PermissionAdmin($roleSuperAdmin, $superAdmin);
        $admin->store();


        // role owner
        $roleOwner = Role::query()->updateOrCreate([
            'name' => 'owner',
            'guard_name' => 'web'
        ], [
            'name' => 'owner',
            'guard_name' => 'web'
        ]);
        $owner = new PermissionOwner($roleOwner);
        $owner->store();
    }
}
