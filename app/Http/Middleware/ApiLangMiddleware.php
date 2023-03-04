<?php

namespace App\Http\Middleware;

use Closure;

class ApiLangMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->is('api/*') && $request->header('lang')) {
            app()->setLocale($request->header('lang'));
        }
        return $next($request);
    }
}
