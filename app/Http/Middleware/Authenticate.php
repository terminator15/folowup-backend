<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Disable redirect for unauthenticated users.
     * API-only application.
     */
    protected function redirectTo($request): ?string
    {
        return null;
    }
}
