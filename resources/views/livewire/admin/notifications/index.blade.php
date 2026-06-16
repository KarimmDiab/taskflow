<div dir="ltr" class="min-h-screen bg-stone-50 px-4 py-8 text-slate-950 dark:bg-slate-950 dark:text-white sm:px-6 lg:px-8">
    <div class="mx-auto max-w-[1500px] space-y-6">
        @if (session()->has('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 dark:border-emerald-900/40 dark:bg-emerald-950/30 dark:text-emerald-200">
                {{ session('success') }}
            </div>
        @endif

        <section class="overflow-hidden rounded-2xl border border-stone-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="border-b border-stone-100 bg-gradient-to-r from-stone-50 via-white to-rose-50 px-6 py-6 dark:border-slate-800 dark:from-slate-900 dark:via-slate-900 dark:to-rose-950/20">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-rose-500">Admin center</p>
                        <h1 class="mt-2 text-3xl font-bold tracking-tight">Notifications</h1>
                        <p class="mt-2 max-w-2xl text-sm text-slate-500 dark:text-slate-400">
                            Track online orders, stock changes, retail sales, and user activity from one operational inbox.
                        </p>
                    </div>

                    @can('notifications.update')
                        <button type="button" wire:click="markAllAsRead"
                            class="inline-flex items-center justify-center rounded-xl bg-slate-950 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-700 dark:bg-white dark:text-slate-950">
                            Mark all as read
                        </button>
                    @endcan
                </div>
            </div>

            <div class="grid gap-3 border-b border-stone-100 p-5 dark:border-slate-800 md:grid-cols-2 xl:grid-cols-5">
                <div class="xl:col-span-2">
                    <label class="mb-1 block text-xs font-semibold text-slate-500">Search</label>
                    <input type="search" wire:model.live.debounce.300ms="search" placeholder="Search title or message"
                        class="w-full rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm shadow-sm outline-none transition focus:border-rose-400 focus:ring-4 focus:ring-rose-100 dark:border-slate-700 dark:bg-slate-950 dark:focus:ring-rose-950/40">
                </div>

                <div>
                    <label class="mb-1 block text-xs font-semibold text-slate-500">Read status</label>
                    <select wire:model.live="status"
                        class="w-full rounded-xl border border-stone-200 bg-white px-3 py-2.5 text-sm shadow-sm outline-none focus:border-rose-400 focus:ring-4 focus:ring-rose-100 dark:border-slate-700 dark:bg-slate-950">
                        <option value="">All</option>
                        <option value="unread">Unread</option>
                        <option value="read">Read</option>
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-semibold text-slate-500">Module</label>
                    <select wire:model.live="module"
                        class="w-full rounded-xl border border-stone-200 bg-white px-3 py-2.5 text-sm shadow-sm outline-none focus:border-rose-400 focus:ring-4 focus:ring-rose-100 dark:border-slate-700 dark:bg-slate-950">
                        <option value="">All modules</option>
                        @foreach ($this->modules as $moduleName)
                            <option value="{{ $moduleName }}">{{ str_replace('_', ' ', $moduleName) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <select wire:model.live="perPage"
                        class="w-full rounded-xl border border-stone-200 bg-white px-3 py-2.5 text-sm shadow-sm outline-none dark:border-slate-700 dark:bg-slate-950">
                        <option value="15">15 per page</option>
                        <option value="30">30 per page</option>
                        <option value="50">50 per page</option>
                    </select>
                    <button type="button" wire:click="resetFilters"
                        class="rounded-xl border border-stone-200 px-3 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-stone-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                        Reset
                    </button>
                </div>
            </div>

            <div class="relative">
                <div wire:loading.flex class="absolute inset-0 z-20 items-center justify-center bg-white/75 backdrop-blur-sm dark:bg-slate-950/75">
                    <div class="rounded-xl border border-stone-200 bg-white px-5 py-3 text-sm font-semibold shadow-lg dark:border-slate-800 dark:bg-slate-900">
                        Loading notifications...
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[960px] text-sm">
                        <thead class="bg-stone-50 text-xs uppercase tracking-wide text-slate-500 dark:bg-slate-950/60">
                            <tr>
                                <th class="px-5 py-3 text-left">Notification</th>
                                <th class="px-5 py-3 text-left">Type</th>
                                <th class="px-5 py-3 text-left">Module</th>
                                <th class="px-5 py-3 text-left">Status</th>
                                <th class="px-5 py-3 text-left">Created</th>
                                <th class="px-5 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 dark:divide-slate-800">
                            @forelse ($this->notifications as $notification)
                                @php($link = $this->notificationLink($notification))

                                <tr wire:key="notification-row-{{ $notification->id }}" class="transition hover:bg-rose-50/40 dark:hover:bg-rose-950/10">
                                    <td class="px-5 py-4">
                                        <div class="flex items-start gap-3">
                                            <span class="mt-1.5 h-2.5 w-2.5 rounded-full {{ $notification->is_read ? 'bg-slate-300 dark:bg-slate-700' : 'bg-rose-500' }}"></span>
                                            <div>
                                                <p class="font-semibold text-slate-950 dark:text-white">{{ $notification->title }}</p>
                                                <p class="mt-1 max-w-2xl text-sm leading-6 text-slate-500 dark:text-slate-400">{{ $notification->message }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                                            {{ str_replace('_', ' ', $notification->type) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4">{{ str_replace('_', ' ', $notification->module) }}</td>
                                    <td class="px-5 py-4">
                                        @if ($notification->is_read)
                                            <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200 dark:bg-emerald-950/30 dark:text-emerald-300 dark:ring-emerald-900">Read</span>
                                        @else
                                            <span class="rounded-full bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700 ring-1 ring-rose-200 dark:bg-rose-950/30 dark:text-rose-300 dark:ring-rose-900">Unread</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="font-medium">{{ $notification->created_at?->format('Y-m-d') }}</div>
                                        <div class="text-xs text-slate-400">{{ $notification->created_at?->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex items-center justify-end gap-2">
                                            @if ($link)
                                                <a href="{{ $link }}" wire:navigate
                                                    class="rounded-lg bg-slate-950 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-rose-700 dark:bg-white dark:text-slate-950">
                                                    Open
                                                </a>
                                            @endif

                                            @can('notifications.update')
                                                @unless ($notification->is_read)
                                                    <button type="button" wire:click="markAsRead({{ $notification->id }})"
                                                        class="rounded-lg border border-stone-200 px-3 py-1.5 text-xs font-semibold transition hover:bg-stone-50 dark:border-slate-700 dark:hover:bg-slate-800">
                                                        Mark read
                                                    </button>
                                                @endunless
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-20 text-center">
                                        <div class="mx-auto flex max-w-sm flex-col items-center">
                                            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-stone-100 dark:bg-slate-800">
                                                <svg class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                            <h3 class="mt-4 text-lg font-bold">No notifications found</h3>
                                            <p class="mt-1 text-sm text-slate-500">Try changing filters or wait for new admin activity.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($this->notifications->hasPages())
                    <div class="border-t border-stone-100 px-5 py-4 dark:border-slate-800">
                        {{ $this->notifications->links() }}
                    </div>
                @endif
            </div>
        </section>
    </div>
</div>
