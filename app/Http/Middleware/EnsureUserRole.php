<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $userRole = strtolower($request->user()?->role ?? '');
        $allowedRoles = array_map('strtolower', $roles);
        abort_unless(in_array($userRole, $allowedRoles), 403);

        return $next($request);
    }
}
