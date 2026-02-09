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
        $user = auth()->user();
        if (!$user) {
            return $next($request);
        }

        // SuperAdmins always have access
        if ($user->is_super_admin) {
            return $next($request);
        }

        $company = $user->company;
        if (!$company) {
            return $next($request);
        }

        // Default allowed if settings not initialized, otherwise check array
        $activeModules = $company->settings['modules'] ?? ['Accounting', 'CRM', 'Inventory'];

        if (!in_array($module, $activeModules)) {
            abort(403, "Bu modül ({$module}) şirketiniz için aktif edilmemiştir. Lütfen sistem yöneticisi ile iletişime geçin.");
        }

        return $next($request);
    }
}
