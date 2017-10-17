<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\AuthService;

class IsLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @param null                      $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $authService = new AuthService;
        if (! $authService->role($guard)->check()) {
            return redirect('/auth/login');
        }

        return $next($request);
    }
}
