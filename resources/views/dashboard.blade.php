<x-layouts::app :title="__('Dashboard')">
    @php
        $pendingOnlineOrders = \App\Models\OnlineOrder::query()->where('status', 'pending')->count();
    @endphp

    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            @can('orders.view')
                <a href="{{ route('orders') }}" wire:navigate
                    class="group relative aspect-video overflow-hidden rounded-xl border border-rose-100 bg-gradient-to-br from-white via-rose-50 to-stone-100 p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-xl dark:border-rose-900/30 dark:from-slate-900 dark:via-rose-950/20 dark:to-slate-950">
                    <div class="flex h-full flex-col justify-between">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-rose-500">Online orders</p>
                                <h2 class="mt-2 text-lg font-bold text-slate-950 dark:text-white">New pending orders</h2>
                            </div>
                            <div class="rounded-xl bg-white/80 px-3 py-2 text-xs font-semibold text-rose-600 shadow-sm dark:bg-slate-900/80 dark:text-rose-300">
                                Live
                            </div>
                        </div>
                        <div>
                            <p class="text-5xl font-black tracking-tight text-slate-950 dark:text-white">{{ number_format($pendingOnlineOrders) }}</p>
                            <p class="mt-2 text-sm font-medium text-slate-500 dark:text-slate-400">Awaiting confirmation and fulfillment.</p>
                        </div>
                    </div>
                </a>
            @else
                <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                    <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
                </div>
            @endcan
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
        </div>
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
        </div>
    </div>
</x-layouts::app>
