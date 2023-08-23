<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $roles)
    {
        $user = auth()->user();

        $roles = is_array($roles)
            ? $roles
            : explode('|', $roles);

        foreach ($roles as $role) {

            if($user->hasRole($role)) return $next($request);
        }

        return response()->json([
            'message' => Config::get('app.messages.auth.invalid_roles')
        ], 401);
    }
}
