<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">

<head>
    @include('partials.head')
    <style>
        /* ==================== PROFESSIONAL SIDEBAR & LAYOUT ENHANCEMENTS ==================== */
        :root {
            --sidebar-width: 280px;
            --navbar-height: 4.25rem;
        }

        /* ---------- SIDEBAR CONTAINER ---------- */
        flux\:sidebar.sidebar-enhanced,
        .sidebar-enhanced {
            width: var(--sidebar-width) !important;
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(16px) !important;
            border-inline-end: 1px solid rgba(0, 0, 0, 0.06) !important;
            /* logical end border for RTL/LTR */
            box-shadow: 8px 0 24px rgba(0, 0, 0, 0.04), 2px 0 6px rgba(0, 0, 0, 0.03) !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        .dark .sidebar-enhanced {
            background: rgba(24, 24, 27, 0.96) !important;
            border-inline-end-color: rgba(255, 255, 255, 0.08) !important;
            box-shadow: 8px 0 24px rgba(0, 0, 0, 0.5) !important;
        }

        /* On desktop, fix the sidebar below the top navbar on the right side */
        @media (min-width: 1024px) {
            .sidebar-enhanced {
                position: fixed !important;
                inset-block-start: var(--navbar-height) !important;
                /* fix to right edge in RTL */
                inset-inline-start: 0 !important;
                inset-inline-end: auto !important;
                height: calc(100vh - var(--navbar-height)) !important;
                margin: 0 !important;
                z-index: 30;
            }
        }

        /* ---------- SIDEBAR HEADER ---------- */
        .sidebar-header-enhanced {
            position: relative;
            padding: 1.25rem 1rem 1rem;
            margin-bottom: 0.5rem;
            border-bottom: none !important;
        }

        .sidebar-header-enhanced::after {
            content: '';
            position: absolute;
            bottom: 0;
            inset-inline-end: 1rem;
            /* logical end: stays near screen edge */
            width: 48px;
            height: 3px;
            border-radius: 2px;
            transition: width 0.3s ease;
        }

        .sidebar-header-enhanced:hover::after {
            width: 72px;
        }

        /* Logo wrapper */
        .sidebar-logo-wrapper {
            width: 100%;
            border-radius: 14px;
            padding: 0.45rem 0.55rem;

            transition: background-color 0.18s ease, border-color 0.18s ease, transform 0.18s ease;
        }

        .sidebar-logo-wrapper:hover {
            background: #ffffff;
            border-color: rgba(148, 163, 184, 0.45);
            transform: translateY(-1px);
        }

        .dark .sidebar-logo-wrapper {
            background: rgba(24, 24, 27, 0.72);
            border-color: rgba(63, 63, 70, 0.8);
        }

        .dark .sidebar-logo-wrapper:hover {
            background: rgba(39, 39, 42, 0.88);
            border-color: rgba(82, 82, 91, 0.95);
        }

        /* ---------- NAVIGATION GROUP HEADINGS ---------- */
        .sidebar-group-heading {
            font-size: 0.68rem !important;
            font-weight: 700 !important;
            letter-spacing: 0.08em !important;
            text-transform: uppercase !important;
            color: #6b7280 !important;
            margin: 0.75rem 0 0.75rem 0.25rem !important;
            padding-inline-end: 0.75rem !important;
        }

        .dark .sidebar-group-heading {
            color: #9ca3af !important;
        }

        /* ---------- SIDEBAR ITEMS ---------- */
        .sidebar-item-enhanced {
            position: relative !important;
            border-radius: 14px !important;
            margin: 3px 0 !important;
            padding: 0.65rem 1rem !important;
            font-weight: 500 !important;
            transition: all 0.25s cubic-bezier(0.2, 0.9, 0.4, 1.1) !important;
            overflow: hidden !important;
            color: #334155 !important;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .dark .sidebar-item-enhanced {
            color: #cbd5e1 !important;
        }

        .sidebar-item-enhanced:hover {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.08) 0%, rgba(139, 92, 246, 0.04) 100%) !important;
            transform: translateX(-3px) !important;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.04) !important;
        }

        .dark .sidebar-item-enhanced:hover {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.15) 0%, rgba(139, 92, 246, 0.08) 100%) !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3) !important;
        }

        /* Active state – refined accent on the inner edge (adjacent to content) */
        .sidebar-item-enhanced.active,
        .sidebar-item-enhanced[aria-current="page"],
        .sidebar-item-enhanced.current {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.14) 0%, rgba(139, 92, 246, 0.08) 100%) !important;
            border-inline-start: 3px solid #3b82f6 !important;
            /* accent on the content side */
            box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.2), 0 4px 8px rgba(0, 0, 0, 0.05) !important;
            font-weight: 600 !important;
            color: #1e293b !important;
        }

        .dark .sidebar-item-enhanced.active,
        .dark .sidebar-item-enhanced[aria-current="page"] {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.25) 0%, rgba(139, 92, 246, 0.15) 100%) !important;
            border-inline-start-color: #60a5fa !important;
            box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.08), 0 6px 14px rgba(0, 0, 0, 0.4) !important;
            color: #f1f5f9 !important;
        }

        /* Icon styling */
        .sidebar-item-enhanced svg,
        .sidebar-item-enhanced flux\:icon {
            width: 1.4rem !important;
            height: 1.4rem !important;
            stroke-width: 1.6 !important;
            transition: transform 0.2s ease, color 0.2s !important;
            opacity: 0.8;
            flex-shrink: 0;
        }

        .sidebar-item-enhanced:hover svg {
            transform: scale(1.06) !important;
            opacity: 1;
        }

        .sidebar-item-enhanced.active svg {
            opacity: 1;
            color: #3b82f6 !important;
        }

        .dark .sidebar-item-enhanced.active svg {
            color: #60a5fa !important;
        }

        /* Group separator */
        .sidebar-group-enhanced {
            margin-bottom: 1.5rem !important;
            position: relative;
        }

        .sidebar-group-enhanced:not(:last-child)::after {
            content: '';
            position: absolute;
            bottom: -0.75rem;
            inset-inline: 1rem;
            /* logical left/right */
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(0, 0, 0, 0.06), transparent);
        }

        .dark .sidebar-group-enhanced:not(:last-child)::after {
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.08), transparent);
        }

        /* Scrollbar */
        .sidebar-enhanced ::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-enhanced ::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-enhanced ::-webkit-scrollbar-thumb {
            background: rgba(156, 163, 175, 0.3);
            border-radius: 20px;
        }

        .sidebar-enhanced ::-webkit-scrollbar-thumb:hover {
            background: rgba(107, 114, 128, 0.5);
        }

        /* Collapse button */
        .sidebar-collapse-btn {
            background: rgba(241, 245, 249, 0.7) !important;
            border-radius: 30px !important;
            transition: all 0.3s !important;
            padding: 0.35rem !important;
        }

        .sidebar-collapse-btn:hover {
            background: rgba(226, 232, 240, 0.9) !important;
            transform: rotate(180deg);
        }

        /* Focus ring */
        .sidebar-item-enhanced:focus-visible {
            outline: 2px solid #3b82f6;
            outline-offset: 2px;
            border-radius: 14px;
        }

        /* ---------- TOP NAVBAR ---------- */
        .admin-top-navbar {
            position: fixed;
            inset-inline: 0;
            top: 0;
            z-index: 40;
            height: var(--navbar-height);
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.9);
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.06);
        }

        .dark .admin-top-navbar {
            background: rgba(24, 24, 27, 0.94);
            border-bottom-color: rgba(39, 39, 42, 0.92);
            box-shadow: 0 10px 28px rgba(0, 0, 0, 0.34);
        }

        /* Leave space for the sidebar content on desktop */
        @media (min-width: 1024px) {
            .admin-content-offset {
                margin-inline-start: var(--sidebar-width);
                /* push main content left of the right-side sidebar */
            }
        }

        /* ---------- MAIN CONTENT OFFSET ---------- */
        .admin-content-offset {
            padding-top: var(--navbar-height);
        }

        @media (min-width: 1024px) {
            .admin-content-offset {
                /* push content away from sidebar */
            }
        }

        /* ---------- MOBILE USER MENU DROPDOWN ---------- */
        .mobile-user-dropdown {
            background: rgba(255, 255, 255, 0.98) !important;
            backdrop-filter: blur(12px) !important;
            border: 1px solid rgba(0, 0, 0, 0.06) !important;
            border-radius: 24px !important;
            overflow: hidden;
        }

        .dark .mobile-user-dropdown {
            background: rgba(24, 24, 27, 0.98) !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
        }

        /* Toast */
        flux\:toast {
            border-radius: 18px !important;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12) !important;
        }
    </style>
</head>

<body class="min-h-screen bg-zinc-50 text-zinc-900 antialiased dark:bg-zinc-950 dark:text-zinc-100" dir="rtl">

    <!-- SIDEBAR (right side in RTL) -->
    <flux:sidebar sticky collapsible="mobile"
        class="sidebar-enhanced border-e border-zinc-200/20 bg-white/95 shadow-2xl backdrop-blur-lg dark:border-zinc-800 dark:bg-zinc-900/90"
        style="--sidebar-width: 280px;">



        <flux:sidebar.nav class="px-3 pt-4 custom-scroll">
            <!-- Dashboard -->
            <flux:sidebar.group :heading="__('الرئيسية')" class="sidebar-group-enhanced mb-6">
                @can('dashboard.view')
                    <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                        wire:navigate class="sidebar-item-enhanced">
                        {{ __('لوحة التحكم') }}
                    </flux:sidebar.item>
                @endcan
            </flux:sidebar.group>

            <!-- Products & Inventory -->
            <flux:sidebar.group :heading="__('إدارة المنتجات والمخزون')" class="sidebar-group-enhanced mb-6">
                @can('stock_movements.view')
                    <flux:sidebar.item icon="arrows-right-left" :href="route('stock-movements.index')"
                        :current="request()->routeIs('stock-movements.index')" wire:navigate class="sidebar-item-enhanced">
                        {{ __('Stock Movements') }}</flux:sidebar.item>
                @endcan
                @can('inventory_adjustments.view')
                    <flux:sidebar.item icon="clipboard-document-check" :href="route('inventory-adjustments.index')"
                        :current="request()->routeIs('inventory-adjustments.index')" wire:navigate class="sidebar-item-enhanced">
                        {{ __('Inventory Adjustments') }}</flux:sidebar.item>
                @endcan
                @can('stock_transfers.view')
                    <flux:sidebar.item icon="truck" :href="route('stock-transfers.index')"
                        :current="request()->routeIs('stock-transfers.index')" wire:navigate class="sidebar-item-enhanced">
                        {{ __('Stock Transfers') }}</flux:sidebar.item>
                @endcan
                @can('barcodes.view')
                    <flux:sidebar.item icon="qr-code" :href="route('barcodes.index')"
                        :current="request()->routeIs('barcodes.index')" wire:navigate class="sidebar-item-enhanced">
                        {{ __('Barcode Labels') }}</flux:sidebar.item>
                @endcan
                @can('products.view')
                    <flux:sidebar.item icon="cube" :href="route('products')" :current="request()->routeIs('products')"
                        wire:navigate class="sidebar-item-enhanced">{{ __('المنتجات') }}</flux:sidebar.item>
                @endcan
                @can('main_categories.view')
                    <flux:sidebar.item icon="tag" :href="route('categories')"
                        :current="request()->routeIs('categories')" wire:navigate class="sidebar-item-enhanced">
                        {{ __('التصنيفات الرئيسية') }}</flux:sidebar.item>
                @endcan
                @can('sub_categories.view')
                    <flux:sidebar.item icon="tag" :href="route('subCategories')"
                        :current="request()->routeIs('subCategories')" wire:navigate class="sidebar-item-enhanced">
                        {{ __('التصنيفات الفرعية') }}</flux:sidebar.item>
                @endcan
            </flux:sidebar.group>

            <!-- POS -->
            <flux:sidebar.group :heading="__('POS')" class="sidebar-group-enhanced mb-6">
                @can('pos_system.view')
                    <flux:sidebar.item icon="shopping-cart" :href="route('pos_system')"
                        :current="request()->routeIs('pos_system')" wire:navigate class="sidebar-item-enhanced">
                        {{ __('POS System') }}</flux:sidebar.item>
                @endcan
            </flux:sidebar.group>

            <!-- Online Store -->
            <flux:sidebar.group :heading="__('Online Store')" class="sidebar-group-enhanced mb-6">
                @can('orders.view')
                    <flux:sidebar.item icon="shopping-bag" :href="route('orders')" :current="request()->routeIs('orders')"
                        wire:navigate class="sidebar-item-enhanced">{{ __('Online Orders') }}</flux:sidebar.item>
                @endcan
                @can('notifications.view')
                    <flux:sidebar.item icon="bell" :href="route('admin.notifications')"
                        :current="request()->routeIs('admin.notifications')" wire:navigate class="sidebar-item-enhanced">
                        {{ __('Notifications') }}</flux:sidebar.item>
                @endcan
            </flux:sidebar.group>

            <!-- Invoices -->
            <flux:sidebar.group :heading="__('إدارة الفواتير')" class="sidebar-group-enhanced mb-6">
                @can('sales.view')
                    <flux:sidebar.item icon="chart-bar-square" :href="route('sales.index')"
                        :current="request()->routeIs('sales.index') || request()->routeIs('sales.show') || request()->routeIs('sales.edit')" wire:navigate class="sidebar-item-enhanced">
                        {{ __('Sales Invoices') }}</flux:sidebar.item>
                @endcan
                @can('sales.return.view')
                    <flux:sidebar.item icon="arrow-uturn-left" :href="route('sales.returns')"
                        :current="request()->routeIs('sales.returns')" wire:navigate class="sidebar-item-enhanced">
                        {{ __('Sales Returns') }}</flux:sidebar.item>
                @endcan
                @can('sales.view')
                    <flux:sidebar.item icon="presentation-chart-line" :href="route('sales.reports')"
                        :current="request()->routeIs('sales.reports')" wire:navigate class="sidebar-item-enhanced">
                        {{ __('Sales Reports') }}</flux:sidebar.item>
                @endcan
                @can('purchase_invoices.view')
                    <flux:sidebar.item icon="document-text" :href="route('purchaseInvoices')"
                        :current="request()->routeIs('purchaseInvoices')" wire:navigate class="sidebar-item-enhanced">
                        {{ __('قائمة فواتير المشتريات') }}</flux:sidebar.item>
                @endcan
                @can('create_purchase_invoice.create')
                    <flux:sidebar.item icon="document-plus" :href="route('createpurchaseInvoices')"
                        :current="request()->routeIs('createpurchaseInvoices')" wire:navigate
                        class="sidebar-item-enhanced">{{ __('تسجيل فاتورة مشتريات جديدة') }}</flux:sidebar.item>
                @endcan
                @can('supplier_payments.view')
                    <flux:sidebar.item icon="banknotes" :href="route('supplier-payments.index')"
                        :current="request()->routeIs('supplier-payments.*')" wire:navigate class="sidebar-item-enhanced">
                        {{ __('Supplier Payments') }}</flux:sidebar.item>
                    <flux:sidebar.item icon="book-open" :href="route('supplier-ledger.index')"
                        :current="request()->routeIs('supplier-ledger.*')" wire:navigate class="sidebar-item-enhanced">
                        {{ __('Supplier Ledger') }}</flux:sidebar.item>
                @endcan
                @can('purchase_returns.view')
                    <flux:sidebar.item icon="arrow-uturn-left" :href="route('purchase-returns.index')"
                        :current="request()->routeIs('purchase-returns.*')" wire:navigate class="sidebar-item-enhanced">
                        {{ __('Purchase Returns') }}</flux:sidebar.item>
                @endcan
            </flux:sidebar.group>
            <!-- Expenses -->
            <flux:sidebar.group :heading="__('إدارة المصروفات')" class="sidebar-group-enhanced mb-6">
                @can('expenses.view')
                    <flux:sidebar.item icon="currency-dollar" :href="route('expenses')"
                        :current="request()->routeIs('expenses')" wire:navigate class="sidebar-item-enhanced">
                        {{ __('جميع المصروفات') }}</flux:sidebar.item>
                @endcan
                @can('expense_items.view')
                    <flux:sidebar.item icon="receipt-percent" :href="route('expenses_items')"
                        :current="request()->routeIs('expenses_items')" wire:navigate class="sidebar-item-enhanced">
                        {{ __('بنود المصروفات') }}</flux:sidebar.item>
                @endcan
            </flux:sidebar.group>

            <!-- Business Relations -->
            <flux:sidebar.group :heading="__('العلاقات التجارية')" class="sidebar-group-enhanced mb-6">
                @can('customers.view')
                    <flux:sidebar.item icon="user-group" :href="route('customers')"
                        :current="request()->routeIs('customers')" wire:navigate class="sidebar-item-enhanced">
                        {{ __('العملاء') }}</flux:sidebar.item>
                @endcan
                @can('suppliers.view')
                    <flux:sidebar.item icon="truck" :href="route('suppliers')"
                        :current="request()->routeIs('suppliers')" wire:navigate class="sidebar-item-enhanced">
                        {{ __('الموردين') }}</flux:sidebar.item>
                @endcan
            </flux:sidebar.group>

            <!-- System Settings -->
            <flux:sidebar.group :heading="__('إعدادات النظام')" class="sidebar-group-enhanced mb-6">
                @can('users.view')
                    <flux:sidebar.item icon="users" :href="route('users')" :current="request()->routeIs('users')"
                        wire:navigate class="sidebar-item-enhanced">{{ __('المستخدمين') }}</flux:sidebar.item>
                @endcan
                @can('users.update')
                    <flux:sidebar.item icon="plus-circle" :href="route('roles.index')"
                        :current="request()->routeIs('roles.index')" wire:navigate class="sidebar-item-enhanced">
                        {{ __('Add Role') }}</flux:sidebar.item>
                    <flux:sidebar.item icon="shield-check" :href="route('roles-permissions.index')"
                        :current="request()->routeIs('roles-permissions.index')" wire:navigate
                        class="sidebar-item-enhanced">{{ __('Roles Permissions') }}</flux:sidebar.item>
                    <flux:sidebar.item icon="user-plus" :href="route('user-roles.index')"
                        :current="request()->routeIs('user-roles.index')" wire:navigate class="sidebar-item-enhanced">
                        {{ __('User Roles') }}</flux:sidebar.item>
                @endcan
                @can('branches.view')
                    <flux:sidebar.item icon="building-storefront" :href="route('branches')"
                        :current="request()->routeIs('branches')" wire:navigate class="sidebar-item-enhanced">
                        {{ __('الفروع') }}</flux:sidebar.item>
                @endcan
                @can('colors.view')
                    <flux:sidebar.item icon="building-storefront" :href="route('colors')"
                        :current="request()->routeIs('colors')" wire:navigate class="sidebar-item-enhanced">
                        {{ __('اضافة الوان') }}</flux:sidebar.item>
                @endcan
                @can('sizes.view')
                    <flux:sidebar.item icon="building-storefront" :href="route('sizes')"
                        :current="request()->routeIs('sizes')" wire:navigate class="sidebar-item-enhanced">
                        {{ __('اضافة مقاس') }}</flux:sidebar.item>
                @endcan
                @can('payment_methods.view')
                    <flux:sidebar.item icon="building-storefront" :href="route('payment_methods')"
                        :current="request()->routeIs('payment_methods')" wire:navigate class="sidebar-item-enhanced">
                        {{ __('طرق الدفع') }}</flux:sidebar.item>
                @endcan
                @can('shipping_governorates.view')
                    <flux:sidebar.item icon="building-storefront" :href="route('shipping')"
                        :current="request()->routeIs('shipping')" wire:navigate class="sidebar-item-enhanced">
                        {{ __('محافظات الشحن') }}</flux:sidebar.item>
                @endcan
                @can('collections.view')
                    <flux:sidebar.item icon="building-storefront" :href="route('all_collections')"
                        :current="request()->routeIs('all_collections')" wire:navigate class="sidebar-item-enhanced">
                        {{ __('كولكشن') }}</flux:sidebar.item>
                @endcan
            </flux:sidebar.group>
        </flux:sidebar.nav>

        <flux:spacer />
    </flux:sidebar>

    <!-- TOP NAVBAR (fixed, adapts to sidebar width on desktop) -->
    <header class="admin-top-navbar">
        <div class="flex h-full items-center justify-between gap-4 px-4 sm:px-6" dir="rtl">
            <div class="flex items-center gap-3">
                <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
                <div class="hidden sm:block">
                    <flux:sidebar.header
                        class="sidebar-header-enhanced border-b border-zinc-200/20 px-4 dark:border-zinc-800">
                        <div class="flex w-full items-center justify-between gap-3">
                            <div class="sidebar-logo-wrapper">
                                <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                            </div>
                        </div>
                        <flux:sidebar.collapse class="sidebar-collapse-btn lg:hidden" />
                        <p class="text-sm font-semibold text-zinc-700 dark:text-zinc-200">
                        {{ now()->format('l, M d') }}
                    </p>
                    </flux:sidebar.header>

                </div>
            </div>

            <div class="flex items-center gap-2">
                @can('notifications.view')
                    <livewire:admin.notifications-dropdown />
                @endcan

                <flux:dropdown position="bottom" align="end" class="mobile-user-dropdown">
                    <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down"
                        class="rounded-full p-1 transition-colors hover:bg-zinc-100 dark:hover:bg-zinc-800" />

                    <flux:menu
                        class="rounded-2xl border border-zinc-200/60 bg-white/95 p-1 shadow-2xl backdrop-blur-md dark:border-zinc-700/60 dark:bg-zinc-900/95">
                        <div class="p-3">
                            <div class="flex items-center gap-3 px-2 py-2">
                                <flux:avatar :name="auth()->user()->name" :initials="auth()->user()->initials()"
                                    class="h-12 w-12 ring-2 ring-blue-500/20" />
                                <div class="grid flex-1 text-start">
                                    <flux:heading class="truncate text-base font-semibold">{{ auth()->user()->name }}
                                    </flux:heading>
                                    <flux:text class="truncate text-xs text-zinc-500 dark:text-zinc-400">
                                        {{ auth()->user()->email }}</flux:text>
                                </div>
                            </div>
                        </div>

                        <flux:menu.separator class="my-0.5 bg-zinc-200/50 dark:bg-zinc-700/50" />

                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate
                            class="mx-1 rounded-xl py-2.5 transition-colors hover:bg-zinc-100 dark:hover:bg-zinc-800">
                            {{ __('Settings') }}
                        </flux:menu.item>

                        <flux:menu.separator class="my-0.5 bg-zinc-200/50 dark:bg-zinc-700/50" />

                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                                class="mx-1 w-full cursor-pointer rounded-xl py-2.5 font-medium text-red-600 transition-colors hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-950/30"
                                data-test="logout-button">
                                {{ __('Log out') }}
                            </flux:menu.item>
                        </form>
                    </flux:menu>
                </flux:dropdown>
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT AREA -->
    <main class="admin-content-offset">
        {{ $slot }}
    </main>

    @persist('toast')
        <flux:toast.group>
            <flux:toast />
        </flux:toast.group>
    @endpersist

    @fluxScripts
    <script src="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.js"></script>

    <script>
        (function() {
            // Apply active class to sidebar items for enhanced styling
            const currentPath = window.location.pathname;
            document.querySelectorAll('.sidebar-item-enhanced').forEach(item => {
                const href = item.getAttribute('href');
                if (href && (currentPath === href || (href !== '/' && currentPath.startsWith(href) && href !==
                        '#'))) {
                    item.classList.add('active', 'current');
                } else if (item.getAttribute('aria-current') === 'page') {
                    item.classList.add('active', 'current');
                }
            });

            // Ensure group headings receive the custom typography class
            document.querySelectorAll('.sidebar-group-enhanced > [data-flux-sidebar-group-heading]').forEach(
            heading => {
                if (!heading.classList.contains('sidebar-group-heading')) {
                    heading.classList.add('sidebar-group-heading');
                }
            });
        })();
    </script>
</body>

</html>
