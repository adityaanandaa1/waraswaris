<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected function redirectTo($request): ?string
    {
        // Kalau request bukan JSON, arahkan ke halaman login milikmu
        return $request->expectsJson() ? null : route('login');
    }
}
