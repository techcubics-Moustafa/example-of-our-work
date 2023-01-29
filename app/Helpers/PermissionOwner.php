<?php

namespace App\Helpers;

use Spatie\Permission\Models\Permission;

class PermissionOwner
{
    public function __construct(public $ownerRole)
    {
    }

    public static function models(): array
    {
        return [
            'Role#Staff',
            'Staff',
            'Chat',
            'Chart',
            'Setting#Account',
            'Notification',
            'Payment',
            'Live#Chat',
        ];
    }

    public static function lists(): array
    {
        return [
            'list', 'add', 'edit', 'delete'
        ];
    }

    public function createPermissions(): void
    {
        foreach ($this->models() as $row) {
            foreach ($this->lists() as $value) {
                Permission::query()->updateOrCreate([
                    'name' => $row . " " . $value,
                    'guard_name' => 'web',
                ], [
                    'name' => $row . " " . $value,
                    'guard_name' => 'web',
                ]);
            }
        }
    }

    public function store(): void
    {
        $this->createPermissions();

        $ownerPermissions = $this->permissions($this->models(), $this->lists());

        $this->ownerRole->givePermissionTo($ownerPermissions);

        //$this->owner->assignRole($this->ownerRole);
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
