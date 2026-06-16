<?php

namespace App\Livewire\Admin\Notifications;

use App\Models\Notification;
use App\Services\NotificationService;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Notifications')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = '';
    public string $module = '';
    public int $perPage = 15;

    public function mount(): void
    {
        abort_unless(auth()->user()?->can('notifications.view'), 403);
    }

    public function updated($property): void
    {
        if (in_array($property, ['search', 'status', 'module', 'perPage'], true)) {
            $this->resetPage();
        }
    }

    public function markAsRead(int $notificationId): void
    {
        abort_unless(auth()->user()?->can('notifications.update'), 403);

        app(NotificationService::class)->markAsRead($notificationId);
        session()->flash('success', 'Notification marked as read.');
    }

    public function markAllAsRead(): void
    {
        abort_unless(auth()->user()?->can('notifications.update'), 403);

        $count = app(NotificationService::class)->markAllAsRead();
        session()->flash('success', "{$count} notifications marked as read.");
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'status', 'module']);
        $this->resetPage();
    }

    public function getNotificationsProperty()
    {
        return app(NotificationService::class)->paginated(
            status: $this->status ?: null,
            module: $this->module ?: null,
            search: trim($this->search) ?: null,
            perPage: $this->perPage,
        );
    }

    public function getModulesProperty()
    {
        return Notification::query()
            ->select('module')
            ->distinct()
            ->orderBy('module')
            ->pluck('module');
    }

    public function notificationLink(Notification $notification): ?string
    {
        return app(NotificationService::class)->linkFor($notification);
    }

    public function render()
    {
        return view('livewire.admin.notifications.index');
    }
}
