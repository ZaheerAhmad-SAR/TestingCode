<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!empty($request->user())) {

                $routeName  =   $request->route()->getName();

            if (hasPermission($request->user(), $routeName)) {

                return $next($request);

            } else {
                
                return redirect()->route('dashboard.index');
            }
        } else {
            return redirect()->to('login');
        }
    }
}
