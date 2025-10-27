<?php

namespace App\Http\Middleware\Auth;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class BlockUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::check() && Auth::user()->is_blocked){

            // Guardar la notificación en sesión antes de cerrar
            session()->flash('blocked_account', true);
            
            // Cerrar la sesión del usuario
            Auth::logout();
            
            // Invalidar la sesión y regenerar el token
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Redirigir al login con la notificación
            return redirect()->route('filament.intelliTower.auth.login');
        }   
        return $next($request);
    }
}
