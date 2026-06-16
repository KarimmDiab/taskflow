<?php

namespace App\Services;

use App\Models\Notification;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class NotificationService
{
    public function create(
        string $title,
        string $message,
        string $type,
        string $module,
        ?int $referenceId = null,
        ?int $userId = null,
        array $data = []
    ): Notification {
        return Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'module' => $module,
            'reference_id' => $referenceId,
            'data' => $data ?: null,
        ]);
    }

    public function createUniqueUnread(
        string $title,
        string $message,
        string $type,
        string $module,
        ?int $referenceId = null,
        ?int $userId = null,
        array $data = []
    ): ?Notification {
        $exists = Notification::query()
            ->unread()
            ->type($type)
            ->where('module', $module)
            ->where('reference_id', $referenceId)
            ->when($userId, fn ($query) => $query->where('user_id', $userId), fn ($query) => $query->whereNull('user_id'))
            ->exists();

        if ($exists) {
            return null;
        }

        return $this->create($title, $message, $type, $module, $referenceId, $userId, $data);
    }

    public function markAsRead(int $notificationId): bool
    {
        $notification = Notification::query()->find($notificationId);

        if (! $notification || $notification->is_read) {
            return false;
        }

        return $notification->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    public function markAllAsRead(?int $userId = null): int
    {
        return Notification::query()
            ->unread()
            ->when($userId, fn ($query) => $query->where('user_id', $userId))
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }

    public function unreadCount(?int $userId = null): int
    {
        return Notification::query()
            ->unread()
            ->when($userId, fn ($query) => $query->where('user_id', $userId))
            ->count();
    }

    public function latest(int $limit = 10, ?int $userId = null): Collection
    {
        return Notification::query()
            ->when($userId, fn ($query) => $query->where('user_id', $userId))
            ->latestFirst()
            ->limit($limit)
            ->get();
    }

    public function paginated(
        ?string $status = null,
        ?string $module = null,
        ?string $search = null,
        int $perPage = 15
    ): LengthAwarePaginator {
        return Notification::query()
            ->when($status === 'unread', fn ($query) => $query->unread())
            ->when($status === 'read', fn ($query) => $query->read())
            ->when($module, fn ($query) => $query->module($module))
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('message', 'like', "%{$search}%");
                });
            })
            ->latestFirst()
            ->paginate($perPage);
    }

    public function linkFor(Notification $notification): ?string
    {
        return match ($notification->module) {
            'online_orders' => route('orders'),
            'inventory' => route('products'),
            'retail_sales' => route('pos_system'),
            'users' => route('users'),
            default => null,
        };
    }
}
