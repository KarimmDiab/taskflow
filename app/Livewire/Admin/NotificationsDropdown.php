<?php

namespace App\Livewire\Admin;

use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Support\Collection;
use Livewire\Component;

class NotificationsDropdown extends Component
{
    public bool $open = false;

    public function getNotificationsProperty(): Collection
    {
        return app(NotificationService::class)->latest(10);
    }

    public function getUnreadCountProperty(): int
    {
        return app(NotificationService::class)->unreadCount();
    }

    public function markAsRead(int $notificationId): void
    {
        abort_unless(auth()->user()?->can('notifications.update'), 403);

        app(NotificationService::class)->markAsRead($notificationId);
    }

    public function markAllAsRead(): void
    {
        abort_unless(auth()->user()?->can('notifications.update'), 403);

        app(NotificationService::class)->markAllAsRead();
    }

    public function notificationLink(Notification $notification): ?string
    {
        return app(NotificationService::class)->linkFor($notification);
    }

    public function render()
    {
        return view('livewire.admin.notifications-dropdown');
    }
}
