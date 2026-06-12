<?php

return [
    'roles' => [
        'developer',
        'manager',
        'user',
        'pos_sales',
    ],

    'actions' => [
        'view' => 'View',
        'create' => 'Create',
        'update' => 'Update',
        'delete' => 'Delete',
    ],

    'modules' => [
        'dashboard' => 'Dashboard',
        'products' => 'Products',
        'main_categories' => 'Main Categories',
        'sub_categories' => 'Sub Categories',
        'purchase_invoices' => 'Purchase Invoices',
        'create_purchase_invoice' => 'Create Purchase Invoice',
        'expenses' => 'Expenses',
        'expense_items' => 'Expense Items',
        'customers' => 'Customers',
        'suppliers' => 'Suppliers',
        'users' => 'Users',
        'branches' => 'Branches',
        'colors' => 'Colors',
        'sizes' => 'Sizes',
        'payment_methods' => 'Payment Methods',
        'shipping_governorates' => 'Shipping Governorates',
        'collections' => 'Collections',
    ],



    'route_permissions' => [
        'dashboard' => 'dashboard.view',
        'branches' => 'branches.view',
        'users' => 'users.view',
        'suppliers' => 'suppliers.view',
        'categories' => 'main_categories.view',
        'subCategories' => 'sub_categories.view',
        'customers' => 'customers.view',
        'expenses_items' => 'expense_items.view',
        'products.create' => 'products.create',
        'colors' => 'colors.view',
        'sizes' => 'sizes.view',
        'payment_methods' => 'payment_methods.view',
        'shipping' => 'shipping_governorates.view',
        'all_collections' => 'collections.view',
        'products.edit' => 'products.update',
        'products' => 'products.view',
        'expenses' => 'expenses.view',
        'purchaseInvoices' => 'purchase_invoices.view',
        'createpurchaseInvoices' => 'create_purchase_invoice.create',
        'roles-permissions.index' => 'users.update',
        'user-roles.index' => 'users.update',
        'roles.index' => 'users.update',
    ],
];
