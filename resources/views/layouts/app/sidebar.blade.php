<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl" dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-zinc-100 text-zinc-900 antialiased dark:bg-zinc-950 dark:text-zinc-100">
    <flux:sidebar sticky collapsible="mobile"
        class="border-e border-zinc-200/80 bg-white/95 shadow-sm backdrop-blur dark:border-zinc-800 dark:bg-zinc-900/90">
        <flux:sidebar.header class="border-b border-zinc-200/80 px-4 py-4 dark:border-zinc-800">
            <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
            <flux:sidebar.collapse class="lg:hidden" />
        </flux:sidebar.header>

        <flux:sidebar.nav class="px-3 pt-4">
            <flux:sidebar.group :heading="__('Workspace')" class="grid gap-1">
                <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                    wire:navigate
                    class="rounded-lg px-3 py-2.5 font-medium transition hover:bg-zinc-100 dark:hover:bg-zinc-800/80">
                    {{ __('لوحة التحكم') }}
                </flux:sidebar.item>
                <flux:sidebar.item icon="user" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                    wire:navigate
                    class="rounded-lg px-3 py-2.5 font-medium transition hover:bg-zinc-100 dark:hover:bg-zinc-800/80">
                    {{ __('المستخدمين') }}
                </flux:sidebar.item>

                <flux:sidebar.item icon="building" :href="route('branches')" :current="request()->routeIs('branches')"
                    wire:navigate
                    class="rounded-lg px-3 py-2.5 font-medium transition hover:bg-zinc-100 dark:hover:bg-zinc-800/80">
                    {{ __('الفروع') }}
                </flux:sidebar.item>
            </flux:sidebar.group>
        </flux:sidebar.nav>



        <flux:spacer />

        <flux:sidebar.nav class="border-t border-zinc-200/80 px-3 pb-4 pt-3 dark:border-zinc-800">
            <flux:sidebar.group :heading="__('Resources')" class="grid gap-1">
                <flux:sidebar.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit"
                    target="_blank"
                    class="rounded-lg px-3 py-2.5 transition hover:bg-zinc-100 dark:hover:bg-zinc-800/80">
                    {{ __('Repository') }}
                </flux:sidebar.item>

                <flux:sidebar.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire"
                    target="_blank"
                    class="rounded-lg px-3 py-2.5 transition hover:bg-zinc-100 dark:hover:bg-zinc-800/80">
                    {{ __('Documentation') }}
                </flux:sidebar.item>
            </flux:sidebar.group>
        </flux:sidebar.nav>

        <div class="hidden border-t border-zinc-200/80 px-3 py-3 dark:border-zinc-800 lg:block">
            <x-desktop-user-menu
                class="w-full rounded-xl bg-zinc-50 px-2 py-2 shadow-sm ring-1 ring-zinc-200/70 dark:bg-zinc-800/60 dark:ring-zinc-700/70"
                :name="auth()->user()->name" />
        </div>
    </flux:sidebar>

    <!-- Mobile User Menu -->
    <flux:header
        class="border-b border-zinc-200/80 bg-white/95 px-4 py-3 shadow-sm backdrop-blur dark:border-zinc-800 dark:bg-zinc-900/90 lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        <flux:dropdown position="top" align="end">
            <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <flux:avatar :name="auth()->user()->name" :initials="auth()->user()->initials()" />

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                        {{ __('Settings') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                        class="w-full cursor-pointer" data-test="logout-button">
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

</body>

</html>
