@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand name="RYO ERP" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content">
            <img src="{{ asset('images/logos/black_logo.png') }}" class="size-5" alt="logo">
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand name="RYO ERP" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content">
            <img src="{{ asset('images/logos/white_logo.png') }}" class="size-5" alt="logo">
        </x-slot>
    </flux:brand>
@endif
