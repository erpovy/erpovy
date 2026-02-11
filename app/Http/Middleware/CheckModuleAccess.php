<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckModuleAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $module
     */
    public function handle(Request $request, Closure $next, string $module): Response
    {
        if (auth()->check() && !auth()->user()->hasModuleAccess($module)) {
            abort(403, "Bu modül ({$module}) şirketiniz için aktif edilmemiştir. Lütfen sistem yöneticisi ile iletişime geçin.");
        }

        return $next($request);
    }
}
