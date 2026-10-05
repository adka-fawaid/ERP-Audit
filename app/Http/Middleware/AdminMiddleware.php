<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        if (!auth()->user()->is_active) {
            auth()->logout();
            return redirect()->route('login')->withErrors([
                'identity' => 'Akun Anda tidak aktif.',
            ]);
        }
        $roleName = strtolower((string) (auth()->user()->role ?? ''));
        if ($roleName !== 'admin') {
            return redirect()->route('dashboard');
        }
        return $next($request);
    }
}