<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DisableCsrfForApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Desabilita CSRF para rotas de auditoria e API do Redmine
        if ($request->is('auditorias*') || $request->is('api/redmine/*')) {
            $request->session()->regenerateToken();
        }

        return $next($request);
    }
}