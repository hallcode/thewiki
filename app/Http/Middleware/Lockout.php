<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Lockout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $lockoutRoles = config('security.permissions.lockout');

        $user = Auth::user();

        if ($user !== null && ($user->roles()->count() === 0 || $user->hasRole($lockoutRoles)))
        {
            abort(403, "Your account is locked out.");
        }

        return $response;
    }
}
