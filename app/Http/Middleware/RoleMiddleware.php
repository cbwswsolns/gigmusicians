<?php

namespace App\Http\Middleware;

use Closure;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request [the request instance]
     * @param \Closure                 $next    [the closure string]
     * @param string                   $role    [the authenticated user role]
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if (!$request->user()->hasRole($role)) {
            return redirect()->route('public.home');
        }
  
        return $next($request);
    }
}
