<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserMiddleware
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

        if (Auth::guard('user-aes')->check()) {
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

        return \App\Helpers\ApiResponse::unauthorized('Unauthorized');
    }
}
