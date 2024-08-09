<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CustomUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Get all guards dynamically from auth configuration
        $guards = array_keys(config('auth.guards'));

        // Loop through each guard and check if there's an authenticated user
        foreach ($guards as $guard) {
            $user = \Illuminate\Support\Facades\Auth::guard($guard)->user();
            if ($user) {
                // Set the user in the request so it can be accessed with $request->user()
                \Illuminate\Support\Facades\Auth::shouldUse($guard);
                $request->setUserResolver(function () use ($user) {
                    return $user;
                });
                break;
            }
        }

        return $next($request);
    }
}
