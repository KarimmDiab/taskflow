<?php

namespace App\Listeners;

use App\Services\NotificationService;
use Illuminate\Auth\Events\Login;

class CreateUserLoginNotification
{
    public function handle(Login $event): void
    {
        app(NotificationService::class)->create(
            title: 'New User Login',
            message: "{$event->user->name} logged in successfully",
            type: 'user_login',
            module: 'users',
            referenceId: $event->user->id,
            data: [
                'user_id' => $event->user->id,
                'user_name' => $event->user->name,
                'email' => $event->user->email,
            ],
        );
    }
}
