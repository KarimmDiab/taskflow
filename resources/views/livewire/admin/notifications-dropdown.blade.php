<div x-data="{ open: false }" class="relative">
    <!-- Trigger button -->
    <button type="button" @click="open = !open"
        class="relative inline-flex h-10 w-10 items-center justify-center rounded-xl border border-zinc-200 bg-white text-zinc-700 shadow-sm transition-all duration-200 hover:bg-zinc-50 hover:shadow-md dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-200 dark:hover:bg-zinc-800"
        :class="{ 'ring-2 ring-offset-2 ring-blue-500/30 dark:ring-offset-zinc-900': open }">

        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022 23.848 23.848 0 005.455 1.31m5.714 0a3 3 0 11-5.714 0" />
        </svg>

        @if ($this->unreadCount > 0)
            <span
                class="absolute -right-1 -top-1 min-w-[20px] rounded-full bg-rose-600 px-1.5 py-0.5 text-center text-[10px] font-bold  text-white shadow-sm ring-2 ring-white dark:ring-zinc-900">
                {{ $this->unreadCount > 99 ? '99+' : $this->unreadCount }}
            </span>
        @endif
    </button>

    <!-- Dropdown panel -->
    <div x-cloak x-show="open"
         @click.outside="open = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-2 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-2 scale-95"
         class="absolute left-0 z-50 mt-3 w-[min(92vw,420px)] overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-2xl dark:border-zinc-800 dark:bg-zinc-950">

        <!-- Header -->
        <div class="flex items-center justify-between border-b border-zinc-100 px-5 py-4 dark:border-zinc-800">
            <div>
                <h3 class="text-sm font-bold text-zinc-950 dark:text-white">Notifications</h3>
                <p class="mt-0.5 text-xs text-zinc-500">{{ number_format($this->unreadCount) }} unread</p>
            </div>

            <div class="flex items-center gap-2">
                @can('notifications.update')
                    @if ($this->unreadCount > 0)
                        <button type="button" wire:click="markAllAsRead"
                            class="rounded-lg px-3 py-1.5 text-xs font-semibold text-rose-600 transition-colors hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-950/30">
                            Mark all
                        </button>
                    @endif
                @endcan

                @can('notifications.view')
                    <a href="{{ route('admin.notifications') }}" wire:navigate
                        class="inline-flex items-center gap-1 rounded-lg border border-zinc-200 px-3 py-1.5 text-xs font-semibold text-zinc-700 transition-colors hover:bg-zinc-50 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800">
                        View all
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                @endcan
            </div>
        </div>

        <!-- Notification list -->
        <div class="max-h-[460px] overflow-y-auto overscroll-contain">
            @forelse ($this->notifications as $notification)
                @php($link = $this->notificationLink($notification))

                <div wire:key="dropdown-notification-{{ $notification->id }}"
                    class="group border-b border-zinc-100 px-5 py-4 transition-colors last:border-0 hover:bg-zinc-50/80 dark:border-zinc-800 dark:hover:bg-zinc-800/60">
                    <div class="flex items-start gap-3.5">
                        <!-- Status dot -->
                        <div class="mt-1.5 flex-shrink-0">
                            <div class="h-2.5 w-2.5 rounded-full {{ $notification->is_read ? 'bg-zinc-300 dark:bg-zinc-700' : 'bg-rose-500 shadow-[0_0_8px_rgba(244,63,94,0.5)]' }}"></div>
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-zinc-950 dark:text-white">{{ $notification->title }}</p>
                                    <p class="mt-1 line-clamp-2 text-xs leading-5 text-zinc-500 dark:text-zinc-400">{{ $notification->message }}</p>
                                </div>
                                <span class="flex-shrink-0 text-[11px] text-zinc-400 dark:text-zinc-500">
                                    {{ $notification->created_at?->diffForHumans() }}
                                </span>
                            </div>

                            <!-- Actions -->
                            <div class="mt-3 flex flex-wrap items-center gap-2">
                                @if ($link)
                                    <a href="{{ $link }}" wire:navigate
                                        class="inline-flex items-center gap-1 rounded-lg bg-zinc-900 px-2.5 py-1.5 text-xs font-semibold text-white shadow-sm transition-colors hover:bg-rose-700 dark:bg-white dark:text-zinc-900 dark:hover:bg-rose-100">
                                        Open
                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" />
                                        </svg>
                                    </a>
                                @endif

                                @can('notifications.update')
                                    @unless ($notification->is_read)
                                        <button type="button" wire:click="markAsRead({{ $notification->id }})"
                                            class="rounded-lg border border-zinc-200 px-2.5 py-1.5 text-xs font-semibold text-zinc-600 transition-colors hover:bg-zinc-100 dark:border-zinc-700 dark:text-zinc-300 dark:hover:bg-zinc-800">
                                            Mark read
                                        </button>
                                    @endunless
                                @endcan

                                <span class="inline-flex items-center rounded-full bg-zinc-100 px-2 py-0.5 text-[11px] font-medium uppercase tracking-wide text-zinc-500 dark:bg-zinc-800 dark:text-zinc-400">
                                    {{ str_replace('_', ' ', $notification->module) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center px-6 py-16">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-zinc-100 dark:bg-zinc-800">
                        <svg class="h-7 w-7 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="mt-4 text-sm font-semibold text-zinc-700 dark:text-zinc-200">No notifications yet</p>
                    <p class="mt-1 text-xs text-zinc-500">Important admin activity will appear here.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
