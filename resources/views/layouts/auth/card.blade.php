<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen antialiased relative overflow-hidden">

    <!-- 🌄 Background Image -->
    <div class="absolute inset-0 bg-cover bg-center"
        style="background-image: url('https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=1920&q=80');">
    </div>

    <!-- 🌫️ Dark Overlay -->
    <div class="absolute inset-0 bg-black/60"></div>

    <!-- ✨ Soft Gradient Overlay -->
    <div class="absolute inset-0 bg-gradient-to-br from-black/70 via-black/50 to-black/80"></div>

    <!-- Floating Blobs -->
    <div class="absolute -top-40 -left-40 w-[500px] h-[500px] bg-blue-500/20 blur-3xl rounded-full"></div>
    <div class="absolute -bottom-40 -right-40 w-[500px] h-[500px] bg-purple-500/20 blur-3xl rounded-full"></div>

    <!-- Main Container -->
    <div class="relative min-h-screen flex items-center justify-center p-6 md:p-10">

        <div class="w-full max-w-md">

            <!-- Logo -->
            <div class="flex flex-col items-center gap-3 mb-6 text-center">
                <a href="{{ route('home') }}" wire:navigate class="flex items-center justify-center w-16 h-16 rounded-2xl
                          bg-white/10 backdrop-blur-xl border border-white/20 shadow-lg">

                    <x-app-logo-icon class="size-8 text-white" />
                </a>

                <h1 class="text-2xl font-bold text-white">
                    {{ config('app.name', 'Laravel') }}
                </h1>

                <p class="text-sm text-white/70">
                    Welcome back 👋 Login to continue
                </p>
            </div>

            <div class="rounded-3xl border border-white/20
            bg-white/15 backdrop-blur-2xl
            shadow-2xl shadow-black/50">

                <div class="px-8 py-8 text-black">

                    <!-- تحسين وضوح النصوص داخل الفورم -->
                    <div
                        class="[&_*]:text-white [&_input]:text-black [&_input]:bg-white [&_input]:placeholder:text-gray-500">

                        {{ $slot }}

                    </div>

                </div>
            </div>

            <!-- Footer -->
            <p class="text-center text-xs text-white/50 mt-6">
                © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>

        </div>
    </div>

    <!-- Toast -->
    @persist('toast')
    <flux:toast.group>
        <flux:toast />
    </flux:toast.group>
    @endpersist

    @fluxScripts
</body>

</html>