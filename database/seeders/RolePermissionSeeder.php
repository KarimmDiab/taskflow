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
        // reset cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // permissions
        $permissions = [
            'users.view', 'users.create', 'users.update', 'users.delete',
            'suppliers.view', 'suppliers.create', 'suppliers.update', 'suppliers.delete',
            'supplier_payments.view', 'supplier_payments.create', 'supplier_payments.update', 'supplier_payments.delete',
            'purchase_invoices.view', 'purchase_invoices.create', 'purchase_invoices.update', 'purchase_invoices.delete',
            'categories.view', 'categories.create', 'categories.update', 'categories.delete',
            'branches.view', 'branches.create', 'branches.update', 'branches.delete',
            'products.view', 'products.create', 'products.update', 'products.delete',
            'sales_invoices.view', 'sales_invoices.create', 'sales_invoices.update', 'sales_invoices.delete',
            'customers.view', 'customers.create', 'customers.update', 'customers.delete',
            'customer_transactions.view', 'customer_transactions.create', 'customer_transactions.update', 'customer_transactions.delete',
            'expenses_items.view', 'expenses_items.create', 'expenses_items.update', 'expenses_items.delete',
            'expenses_details.view', 'expenses_details.create', 'expenses_details.update', 'expenses_details.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // ADMIN (full access)
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->givePermissionTo(Permission::all());

        // EDITOR (limited access)
        $editorRole = Role::firstOrCreate(['name' => 'editor', 'guard_name' => 'web']);
        $editorRole->givePermissionTo([
            'suppliers.view',
            'suppliers.create',
            'suppliers.update',

            'supplier_payments.view',
            'supplier_payments.create',
            'supplier_payments.update',

            'purchase_invoices.view',
            'purchase_invoices.create',
            'purchase_invoices.update',

            'categories.view',
            'categories.create',
            'categories.update',

            'products.view',
            'products.create',
            'products.update',

            'sales_invoices.view',
            'sales_invoices.create',

            'customer_transactions.view',
            'customer_transactions.create',

            'expenses_items.view',
            'expenses_items.create',
            'expenses_items.update',

            'expenses_details.view',
            'expenses_details.create',
        ]);

        // VIEWER (read only)
        $viewerRole = Role::firstOrCreate(['name' => 'viewer', 'guard_name' => 'web']);
        $viewerRole->givePermissionTo([
            'suppliers.view',
            'supplier_payments.view',
            'purchase_invoices.view',
            'categories.view',
            'products.view',
            'sales_invoices.view',
            'customer_transactions.view',
            'expenses_items.view',
            'expenses_details.view',
        ]);
    }
}
