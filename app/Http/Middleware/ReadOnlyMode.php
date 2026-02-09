<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReadOnlyMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If the session has 'is_inspecting' flag, block all writing methods
        if (session()->has('is_inspecting')) {
            if (!$request->isMethod('GET') && !$request->isMethod('HEAD')) {
                return back()->with('error', 'Gözetim modundasınız. Herhangi bir veriyi değiştiremezsiniz.');
            }
        }

        return $next($request);
    }
}
