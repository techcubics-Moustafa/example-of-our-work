<?php

namespace App\Helpers;

use Spatie\Permission\Models\Permission;

class PermissionAdmin
{

    public function __construct(public $adminRole, public $admin)
    {
       //
    }

    public static function models(): array
    {
        return [
            'Language',
            'Country',
            'Governorate',
            'Region',
            'Category',
            'Special',
            'Feature',
            'Service',
            'User',
            'Company',
            'Role',
            'Employee',
            'Setting',
            'Property',
            'Project',
            'Social#Media',
            'Report#Comment',
            'Page#Setup',
            'Currency',
            'Setting#Account',
        ];
    }

    public static function lists(): array
    {
        return [
            'list', 'add', 'edit', 'delete',
        ];
    }

    public function createPermissions(): void
    {
        foreach ($this->models() as $row) {
            foreach ($this->lists() as $value) {
                Permission::query()->updateOrCreate([
                    'name' => $row . " " . $value,
                    'guard_name' => 'admin'
                ], [
                    'name' => $row . " " . $value,
                    'guard_name' => 'admin'
                ]);
            }
        }
    }

    public function store(): void
    {
        $this->createPermissions();

        $adminPermissions = $this->permissions($this->models(), $this->lists());

        $this->adminRole->givePermissionTo($adminPermissions);


        $this->admin->assignRole($this->adminRole);
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
}
