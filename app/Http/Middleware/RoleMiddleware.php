<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();
        if ($user === null || ! in_array($user->role, $roles, true)) {
            abort(403, 'Anda tidak punya akses ke halaman ini');
        }

        if ($user->role === User::ROLE_SELLER && ! $user->is_active) {
            abort(403, 'Akun seller sedang nonaktif. Hubungi admin.');
        }

        return $next($request);
    }
}
