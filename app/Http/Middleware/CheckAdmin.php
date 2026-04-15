<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Si no está logueado o no es Administrador, lo mandamos fuera
        if (!Auth::check() || Auth::user()->rol !== 'Administrador') {
            return redirect()->route('dashboard')->with('error', 'Acceso denegado.');
        }

        return $next($request);
    }
}