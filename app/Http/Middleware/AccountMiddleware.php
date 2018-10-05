<?php

namespace App\Http\Middleware;

use Closure;

class AccountMiddleware
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
        $value = session('is_login', '0');
        if ($value == "0") {
            return redirect()->route("login.show");
        }
        return $next($request);
    }
}
