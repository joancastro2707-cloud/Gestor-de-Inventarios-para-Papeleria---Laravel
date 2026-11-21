<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Log;

class LogAuthenticationEvents
{
    public function handleLogin(Login $event): void
    {
        /** @var \App\Models\User $user */
        $user = $event->user;

        Log::channel('audit')->info('LOGIN_EXITOSO', [
            'user_id' => $user->id,
            'email' => $user->email,
            'guard' => $event->guard,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function handleLogout(Logout $event): void
    {
        /** @var \App\Models\User|null $user */
        $user = $event->user;

        Log::channel('audit')->info('LOGOUT', [
            'user_id' => $user?->id,
            'email' => $user->email ?? 'desconocido',
            'guard' => $event->guard,
            'ip' => request()->ip(),
        ]);
    }

    public function handleFailed(Failed $event): void
    {
        Log::channel('audit')->warning('LOGIN_FALLIDO', [
            'email' => $event->credentials['email'] ?? 'no_proporcionado',
            'guard' => $event->guard,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}