<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleInsecureRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Adicione o cabeçalho Content-Security-Policy aqui
        if (config('app.url') !== 'http://127.0.0.1:8000') {
            $response->headers->set('Content-Security-Policy', "upgrade-insecure-requests");
        }

        return $response;
    }
}
