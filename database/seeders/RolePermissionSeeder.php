<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $modules = array_keys(config('rbac.modules'));
        $actions = array_keys(config('rbac.actions'));
        $permissions = [];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                $permissions[] = "{$module}.{$action}";
            }
        }

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        foreach (config('rbac.roles') as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        Role::findByName('developer')->syncPermissions($permissions);
        Role::findByName('manager')->syncPermissions($permissions);
        Role::findByName('user')->syncPermissions(config('rbac.default_role_permissions.user'));
        Role::findByName('pos_sales')->syncPermissions(config('rbac.default_role_permissions.pos_sales'));

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
