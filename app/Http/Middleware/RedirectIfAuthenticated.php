<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $role = Auth::guard($guard)->user()->role ?? null;

                return redirect(match ($role) {
                    'pasien'      => route('pasien.dashboard'),
                    'dokter'      => route('dokter.dashboard'),
                    'resepsionis' => route('resepsionis.dashboard'),
                    default       => route('login'),
                });
            }
        }

        return $next($request);
    }
}
