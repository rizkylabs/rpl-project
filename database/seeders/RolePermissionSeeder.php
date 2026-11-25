<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $roles = [
            'admin' => 'access.admin',
            'teacher' => 'access.teacher',
            'student' => 'access.student',
            'parent' => 'access.parent',
            'school' => 'access.school',
        ];

        foreach ($roles as $roleName => $permissionName) {
            $permission = Permission::findOrCreate(
                $permissionName,
                'web'
            );

            $role = Role::findOrCreate(
                $roleName,
                'web'
            );

            $role->givePermissionTo($permission);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->command?->info(
            'EduSync roles and permissions initialized.'
        );
    }
}