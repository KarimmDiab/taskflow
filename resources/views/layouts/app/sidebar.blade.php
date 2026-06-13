<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    @include('partials.head')
    <style>
        /* ==================== PROFESSIONAL SIDEBAR ENHANCEMENTS ==================== */
        /* Custom CSS to elevate the design of Flux sidebar components */

        /* Sidebar container - enhanced glassmorphism & depth */
        flux\:sidebar.sidebar-enhanced,
        .sidebar-enhanced {
            --sidebar-width: 280px;
            width: var(--sidebar-width) !important;
            background: rgba(255, 255, 255, 0.92) !important;
            backdrop-filter: blur(12px) !important;
            border-left: 1px solid rgba(0, 0, 0, 0.05) !important;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.02), 2px 0 8px rgba(0, 0, 0, 0.03) !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        /* Dark mode glass refinement */
        .dark .sidebar-enhanced {
            background: rgba(24, 24, 27, 0.94) !important;
            backdrop-filter: blur(12px) !important;
            border-left: 1px solid rgba(255, 255, 255, 0.05) !important;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.3) !important;
        }

        /* Sidebar header - modern separator with gradient */
        .sidebar-header-enhanced {
            position: relative;
            padding-bottom: 1rem !important;
            margin-bottom: 0.5rem !important;
            border-bottom: none !important;
        }

        .sidebar-header-enhanced::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            width: 40px;
            height: 2px;
            background: linear-gradient(90deg, #3b82f6, #06b6d4, #8b5cf6);
            border-radius: 2px;
            transition: width 0.2s ease;
        }

        /* Navigation group headings - elevated typography */
        .sidebar-group-heading {
            font-size: 0.7rem !important;
            font-weight: 700 !important;
            letter-spacing: 0.05em !important;
            text-transform: uppercase !important;
            color: #6b7280 !important;
            margin-bottom: 0.75rem !important;
            margin-top: 0.5rem !important;
            padding-right: 0.75rem !important;
            position: relative;
            display: inline-block;
        }

        .dark .sidebar-group-heading {
            color: #9ca3af !important;
        }

        /* Professional sidebar items - modern interactive states */
        .sidebar-item-enhanced {
            position: relative !important;
            border-radius: 12px !important;
            margin: 4px 0 !important;
            padding: 0.625rem 0.875rem !important;
            font-weight: 500 !important;
            transition: all 0.2s cubic-bezier(0.2, 0.9, 0.4, 1.1) !important;
            overflow: hidden !important;
            backdrop-filter: blur(4px) !important;
        }

        /* Hover effect with subtle background and scale */
        .sidebar-item-enhanced:hover {
            background: linear-gradient(135deg, rgba(59,130,246,0.08) 0%, rgba(6,182,212,0.04) 100%) !important;
            transform: translateX(-2px) !important;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02) !important;
        }

        .dark .sidebar-item-enhanced:hover {
            background: linear-gradient(135deg, rgba(59,130,246,0.12) 0%, rgba(6,182,212,0.08) 100%) !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2) !important;
        }

        /* Active state - professional accent border + subtle gradient */
        .sidebar-item-enhanced.active,
        .sidebar-item-enhanced[aria-current="page"],
        .sidebar-item-enhanced.current {
            background: linear-gradient(135deg, rgba(59,130,246,0.12) 0%, rgba(139,92,246,0.06) 100%) !important;
            border-right: 3px solid #3b82f6 !important;
            box-shadow: inset 0 1px 1px rgba(255,255,255,0.1), 0 2px 6px rgba(0,0,0,0.02) !important;
            font-weight: 600 !important;
        }

        .dark .sidebar-item-enhanced.active,
        .dark .sidebar-item-enhanced[aria-current="page"] {
            background: linear-gradient(135deg, rgba(59,130,246,0.2) 0%, rgba(139,92,246,0.12) 100%) !important;
            border-right: 3px solid #60a5fa !important;
            box-shadow: inset 0 1px 1px rgba(255,255,255,0.05), 0 4px 10px rgba(0,0,0,0.2) !important;
        }

        /* Icon styling - modern consistent sizing */
        .sidebar-item-enhanced svg,
        .sidebar-item-enhanced flux\:icon {
            width: 1.35rem !important;
            height: 1.35rem !important;
            stroke-width: 1.5 !important;
            transition: transform 0.2s ease !important;
        }

        .sidebar-item-enhanced:hover svg {
            transform: scale(1.02) !important;
        }

        /* Group container spacing */
        .sidebar-group-enhanced {
            margin-bottom: 1.75rem !important;
            position: relative;
        }

        /* Subtle separator between groups (except last) */
        .sidebar-group-enhanced:not(:last-child)::after {
            content: '';
            position: absolute;
            bottom: -0.875rem;
            right: 0.75rem;
            left: 0.75rem;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(0,0,0,0.06), transparent);
        }

        .dark .sidebar-group-enhanced:not(:last-child)::after {
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.06), transparent);
        }

        /* User menu card - premium look */
        .desktop-user-menu-enhanced {
            background: rgba(248, 250, 252, 0.9) !important;
            backdrop-filter: blur(8px) !important;
            border-radius: 20px !important;
            padding: 0.75rem 1rem !important;
            transition: all 0.2s ease !important;
            border: 1px solid rgba(203, 213, 225, 0.4) !important;
        }

        .dark .desktop-user-menu-enhanced {
            background: rgba(39, 39, 42, 0.9) !important;
            border-color: rgba(63, 63, 70, 0.6) !important;
        }

        .desktop-user-menu-enhanced:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
            border-color: rgba(59,130,246,0.3);
        }

        /* Scrollbar customization for sidebar */
        .sidebar-enhanced ::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-enhanced ::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-enhanced ::-webkit-scrollbar-thumb {
            background: rgba(156, 163, 175, 0.4);
            border-radius: 20px;
        }

        .sidebar-enhanced ::-webkit-scrollbar-thumb:hover {
            background: rgba(107, 114, 128, 0.6);
        }

        /* Collapse button enhancement */
        .sidebar-collapse-btn {
            background: rgba(241, 245, 249, 0.7) !important;
            border-radius: 30px !important;
            transition: all 0.2s !important;
        }

        .sidebar-collapse-btn:hover {
            background: rgba(226, 232, 240, 0.9) !important;
            transform: rotate(180deg);
        }

        /* RTL adjustments for active border */
        [dir="rtl"] .sidebar-item-enhanced.active,
        [dir="rtl"] .sidebar-item-enhanced[aria-current="page"] {
            border-right: 3px solid #3b82f6 !important;
            border-left: none !important;
        }

        /* Smooth animation for mobile toggle */
        @media (max-width: 1023px) {
            .sidebar-enhanced {
                transform: translateX(0);
                transition: transform 0.3s ease;
            }
        }

        /* Badge or subtle glow for important items (optional) */
        .sidebar-item-enhanced[data-important="true"]::before {
            content: '';
            position: absolute;
            top: 8px;
            right: 8px;
            width: 6px;
            height: 6px;
            background: #ef4444;
            border-radius: 50%;
            box-shadow: 0 0 0 2px rgba(239,68,68,0.2);
        }

        /* Professional focus ring */
        .sidebar-item-enhanced:focus-visible {
            outline: 2px solid #3b82f6;
            outline-offset: 2px;
            border-radius: 12px;
        }

        /* Header logo area enhancement */
        .sidebar-logo-wrapper {
            transition: opacity 0.2s;
        }

        .sidebar-logo-wrapper:hover {
            opacity: 0.9;
        }
    </style>
</head>

<body class="min-h-screen bg-zinc-100 text-zinc-900 antialiased dark:bg-zinc-950 dark:text-zinc-100">
    <!-- Enhanced Sidebar with professional styling -->
    <flux:sidebar sticky collapsible="mobile"
        class="sidebar-enhanced border-e border-zinc-200/20 bg-white/95 shadow-xl backdrop-blur-lg dark:border-zinc-800 dark:bg-zinc-900/90"
        style="--sidebar-width: 280px;">

        <flux:sidebar.header class="sidebar-header-enhanced border-b border-zinc-200/30 px-4 dark:border-zinc-800" style="padding-bottom: 5px;">
            <div class="sidebar-logo-wrapper">
                <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
            </div>
            <flux:sidebar.collapse class="lg:hidden sidebar-collapse-btn" />
        </flux:sidebar.header>

        <flux:sidebar.nav class="px-3 pt-4 custom-scroll">
            {{-- لوحة التحكم --}}
            <flux:sidebar.group :heading="__('الرئيسية')" class="sidebar-group-enhanced mb-6">
                @can('dashboard.view')
                <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                    wire:navigate
                    class="sidebar-item-enhanced rounded-xl px-3 py-2.5 font-medium transition-all duration-200 hover:bg-zinc-100/70 dark:hover:bg-zinc-800/60">
                    {{ __('لوحة التحكم') }}
                </flux:sidebar.item>
                @endcan
            </flux:sidebar.group>

            {{-- إدارة المنتجات والمخزون --}}
            <flux:sidebar.group :heading="__('إدارة المنتجات والمخزون')" class="sidebar-group-enhanced mb-6">
                @can('products.view')
                <flux:sidebar.item icon="cube" :href="route('products')" :current="request()->routeIs('products')"
                    wire:navigate
                    class="sidebar-item-enhanced rounded-xl px-3 py-2.5 font-medium transition-all duration-200 hover:bg-zinc-100/70 dark:hover:bg-zinc-800/60">
                    {{ __('المنتجات') }}
                </flux:sidebar.item>
                @endcan

                @can('main_categories.view')
                <flux:sidebar.item icon="tag" :href="route('categories')"
                    :current="request()->routeIs('categories')" wire:navigate
                    class="sidebar-item-enhanced rounded-xl px-3 py-2.5 font-medium transition-all duration-200 hover:bg-zinc-100/70 dark:hover:bg-zinc-800/60">
                    {{ __('التصنيفات الرئيسية') }}
                </flux:sidebar.item>
                @endcan

                @can('sub_categories.view')
                <flux:sidebar.item icon="tag" :href="route('subCategories')"
                    :current="request()->routeIs('subCategories')" wire:navigate
                    class="sidebar-item-enhanced rounded-xl px-3 py-2.5 font-medium transition-all duration-200 hover:bg-zinc-100/70 dark:hover:bg-zinc-800/60">
                    {{ __('التصنيفات الفرعية') }}
                </flux:sidebar.item>
                @endcan
            </flux:sidebar.group>

            <flux:sidebar.group :heading="__('POS')" class="sidebar-group-enhanced mb-6">
                @can('pos_system.view')
                <flux:sidebar.item icon="shopping-cart" :href="route('pos_system')"
                    :current="request()->routeIs('pos_system')" wire:navigate
                    class="sidebar-item-enhanced rounded-xl px-3 py-2.5 font-medium transition-all duration-200 hover:bg-zinc-100/70 dark:hover:bg-zinc-800/60">
                    {{ __('POS System') }}
                </flux:sidebar.item>
                @endcan
            </flux:sidebar.group>

            {{-- إدارة الفواتير --}}
            <flux:sidebar.group :heading="__('إدارة الفواتير')" class="sidebar-group-enhanced mb-6">
                @can('purchase_invoices.view')
                <flux:sidebar.item icon="document-text" :href="route('purchaseInvoices')"
                    :current="request()->routeIs('purchaseInvoices')" wire:navigate
                    class="sidebar-item-enhanced rounded-xl px-3 py-2.5 font-medium transition-all duration-200 hover:bg-zinc-100/70 dark:hover:bg-zinc-800/60">
                    {{ __('قائمة فواتير المشتريات') }}
                </flux:sidebar.item>
                @endcan

                @can('create_purchase_invoice.create')
                <flux:sidebar.item icon="document-plus" :href="route('createpurchaseInvoices')"
                    :current="request()->routeIs('createpurchaseInvoices')" wire:navigate
                    class="sidebar-item-enhanced rounded-xl px-3 py-2.5 font-medium transition-all duration-200 hover:bg-zinc-100/70 dark:hover:bg-zinc-800/60">
                    {{ __('تسجيل فاتورة مشتريات جديدة') }}
                </flux:sidebar.item>
                @endcan
            </flux:sidebar.group>

            {{-- إدارة المصروفات --}}
            <flux:sidebar.group :heading="__('إدارة المصروفات')" class="sidebar-group-enhanced mb-6">
                @can('expenses.view')
                <flux:sidebar.item icon="currency-dollar" :href="route('expenses')"
                    :current="request()->routeIs('expenses')" wire:navigate
                    class="sidebar-item-enhanced rounded-xl px-3 py-2.5 font-medium transition-all duration-200 hover:bg-zinc-100/70 dark:hover:bg-zinc-800/60">
                    {{ __('جميع المصروفات') }}
                </flux:sidebar.item>
                @endcan

                @can('expense_items.view')
                <flux:sidebar.item icon="receipt-percent" :href="route('expenses_items')"
                    :current="request()->routeIs('expenses_items')" wire:navigate
                    class="sidebar-item-enhanced rounded-xl px-3 py-2.5 font-medium transition-all duration-200 hover:bg-zinc-100/70 dark:hover:bg-zinc-800/60">
                    {{ __('بنود المصروفات') }}
                </flux:sidebar.item>
                @endcan
            </flux:sidebar.group>

            {{-- إدارة العلاقات التجارية --}}
            <flux:sidebar.group :heading="__('العلاقات التجارية')" class="sidebar-group-enhanced mb-6">
                @can('customers.view')
                <flux:sidebar.item icon="user-group" :href="route('customers')"
                    :current="request()->routeIs('customers')" wire:navigate
                    class="sidebar-item-enhanced rounded-xl px-3 py-2.5 font-medium transition-all duration-200 hover:bg-zinc-100/70 dark:hover:bg-zinc-800/60">
                    {{ __('العملاء') }}
                </flux:sidebar.item>
                @endcan

                @can('suppliers.view')
                <flux:sidebar.item icon="truck" :href="route('suppliers')"
                    :current="request()->routeIs('suppliers')" wire:navigate
                    class="sidebar-item-enhanced rounded-xl px-3 py-2.5 font-medium transition-all duration-200 hover:bg-zinc-100/70 dark:hover:bg-zinc-800/60">
                    {{ __('الموردين') }}
                </flux:sidebar.item>
                @endcan
            </flux:sidebar.group>

            {{-- إعدادات النظام --}}
            <flux:sidebar.group :heading="__('إعدادات النظام')" class="sidebar-group-enhanced mb-6">
                @can('users.view')
                <flux:sidebar.item icon="users" :href="route('users')" :current="request()->routeIs('users')"
                    wire:navigate
                    class="sidebar-item-enhanced rounded-xl px-3 py-2.5 font-medium transition-all duration-200 hover:bg-zinc-100/70 dark:hover:bg-zinc-800/60">
                    {{ __('المستخدمين') }}
                </flux:sidebar.item>
                @endcan

                @can('users.update')
                <flux:sidebar.item icon="plus-circle" :href="route('roles.index')"
                    :current="request()->routeIs('roles.index')" wire:navigate
                    class="sidebar-item-enhanced rounded-xl px-3 py-2.5 font-medium transition-all duration-200 hover:bg-zinc-100/70 dark:hover:bg-zinc-800/60">
                    {{ __('Add Role') }}
                </flux:sidebar.item>

                <flux:sidebar.item icon="shield-check" :href="route('roles-permissions.index')"
                    :current="request()->routeIs('roles-permissions.index')" wire:navigate
                    class="sidebar-item-enhanced rounded-xl px-3 py-2.5 font-medium transition-all duration-200 hover:bg-zinc-100/70 dark:hover:bg-zinc-800/60">
                    {{ __('Roles Permissions') }}
                </flux:sidebar.item>

                <flux:sidebar.item icon="user-plus" :href="route('user-roles.index')"
                    :current="request()->routeIs('user-roles.index')" wire:navigate
                    class="sidebar-item-enhanced rounded-xl px-3 py-2.5 font-medium transition-all duration-200 hover:bg-zinc-100/70 dark:hover:bg-zinc-800/60">
                    {{ __('User Roles') }}
                </flux:sidebar.item>
                @endcan

                @can('branches.view')
                <flux:sidebar.item icon="building-storefront" :href="route('branches')"
                    :current="request()->routeIs('branches')" wire:navigate
                    class="sidebar-item-enhanced rounded-xl px-3 py-2.5 font-medium transition-all duration-200 hover:bg-zinc-100/70 dark:hover:bg-zinc-800/60">
                    {{ __('الفروع') }}
                </flux:sidebar.item>
                @endcan
                @can('colors.view')
                <flux:sidebar.item icon="building-storefront" :href="route('colors')"
                    :current="request()->routeIs('colors')" wire:navigate
                    class="sidebar-item-enhanced rounded-xl px-3 py-2.5 font-medium transition-all duration-200 hover:bg-zinc-100/70 dark:hover:bg-zinc-800/60">
                    {{ __('اضافة الوان') }}
                </flux:sidebar.item>
                @endcan
                @can('sizes.view')
                <flux:sidebar.item icon="building-storefront" :href="route('sizes')"
                    :current="request()->routeIs('sizes')" wire:navigate
                    class="sidebar-item-enhanced rounded-xl px-3 py-2.5 font-medium transition-all duration-200 hover:bg-zinc-100/70 dark:hover:bg-zinc-800/60">
                    {{ __('اضافة مقاس') }}
                </flux:sidebar.item>
                @endcan
                @can('payment_methods.view')
                <flux:sidebar.item icon="building-storefront" :href="route('payment_methods')"
                    :current="request()->routeIs('payment_methods')" wire:navigate
                    class="sidebar-item-enhanced rounded-xl px-3 py-2.5 font-medium transition-all duration-200 hover:bg-zinc-100/70 dark:hover:bg-zinc-800/60">
                    {{ __('طرق الدفع') }}
                </flux:sidebar.item>
                @endcan
                @can('shipping_governorates.view')
                <flux:sidebar.item icon="building-storefront" :href="route('shipping')"
                    :current="request()->routeIs('shipping')" wire:navigate
                    class="sidebar-item-enhanced rounded-xl px-3 py-2.5 font-medium transition-all duration-200 hover:bg-zinc-100/70 dark:hover:bg-zinc-800/60">
                    {{ __('محافظات الشحن') }}
                </flux:sidebar.item>
                @endcan
                @can('collections.view')
                <flux:sidebar.item icon="building-storefront" :href="route('all_collections')"
                    :current="request()->routeIs('all_collections')" wire:navigate
                    class="sidebar-item-enhanced rounded-xl px-3 py-2.5 font-medium transition-all duration-200 hover:bg-zinc-100/70 dark:hover:bg-zinc-800/60">
                    {{ __('كولكشن') }}
                </flux:sidebar.item>
                @endcan
            </flux:sidebar.group>
        </flux:sidebar.nav>

        <flux:spacer />

        <div class="hidden border-t border-zinc-200/20 px-3 pt-3 dark:border-zinc-800 lg:block" style="padding-top: 8px;">
            <x-desktop-user-menu
                class="desktop-user-menu-enhanced w-full rounded-2xl bg-zinc-50/80 px-3 py-2.5 shadow-md ring-1 ring-zinc-200/50 backdrop-blur-sm transition-all duration-200 hover:shadow-lg dark:bg-zinc-800/80 dark:ring-zinc-700/60"
                :name="auth()->user()->name" />
        </div>
    </flux:sidebar>

    <!-- Mobile User Menu with improved design -->
    <flux:header
        class="border-b border-zinc-200/60 bg-white/95 px-4 py-3 shadow-sm backdrop-blur-md dark:border-zinc-800 dark:bg-zinc-900/90 lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        <flux:dropdown position="top" align="end">
            <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down"
                class="hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors rounded-full" />

            <flux:menu class="shadow-2xl border border-zinc-200/50 dark:border-zinc-700/50 rounded-xl backdrop-blur-sm">
                <flux:menu.radio.group>
                    <div class="p-2 text-sm font-normal">
                        <div class="flex items-center gap-3 px-2 py-2 text-start">
                            <flux:avatar :name="auth()->user()->name" :initials="auth()->user()->initials()" class="ring-2 ring-blue-500/20" />

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <flux:heading class="truncate font-semibold">{{ auth()->user()->name }}</flux:heading>
                                <flux:text class="truncate text-xs text-zinc-500 dark:text-zinc-400">{{ auth()->user()->email }}</flux:text>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator class="bg-zinc-200/50 dark:bg-zinc-700/50" />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate
                        class="rounded-lg transition-colors hover:bg-zinc-100 dark:hover:bg-zinc-800">
                        {{ __('Settings') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator class="bg-zinc-200/50 dark:bg-zinc-700/50" />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                        class="w-full cursor-pointer rounded-lg transition-colors hover:bg-red-50 dark:hover:bg-red-950/30 text-red-600 dark:text-red-400" data-test="logout-button">
                        {{ __('Log out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    {{ $slot }}

    @persist('toast')
        <flux:toast.group>
            <flux:toast />
        </flux:toast.group>
    @endpersist

    @fluxScripts
    <script src="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.js"></script>

    <!-- Small script to apply active class based on current route for enhanced styling (works alongside Flux current detection) -->
    <script>
        (function() {
            // Ensure sidebar items get the proper active class for custom styling
            const currentPath = window.location.pathname;
            const sidebarItems = document.querySelectorAll('.sidebar-item-enhanced');
            sidebarItems.forEach(item => {
                const href = item.getAttribute('href');
                if (href && (currentPath === href || (href !== '/' && currentPath.startsWith(href) && href !== '#'))) {
                    item.classList.add('active', 'current');
                } else if (item.getAttribute('aria-current') === 'page') {
                    item.classList.add('active', 'current');
                }
            });

            // Enhance group headings with custom class for typography
            const groupHeadings = document.querySelectorAll('.sidebar-group-enhanced > [data-flux-sidebar-group-heading]');
            groupHeadings.forEach(heading => {
                if (heading && !heading.classList.contains('sidebar-group-heading')) {
                    heading.classList.add('sidebar-group-heading');
                }
            });
        })();
    </script>
</body>

</html>
