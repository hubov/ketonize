<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (Auth::user())
        {
            $user = Auth::user();
            $userRoles = $user->roles->keyBy('name');

            foreach ($roles as $role)
            {
                if (isset($userRoles[$role]))
                {
                    return $next($request);
                }
            }
        }

        abort(403);
    }
}
