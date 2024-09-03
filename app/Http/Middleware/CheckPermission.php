<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permission): Response
    {
        $user = Auth::user();
        if ($user) {
            if($user->status == 1){
                $userPermissions = Auth::user()->features->pluck('name');
                if ($user->position_id === 1 || $userPermissions->contains($permission)) {
                    return $next($request);
                } else {
                    if(str_contains(url()->previous(), '/login')) {
                        return redirect()->route('dashboard');
                    } else {
                        return redirect()->back()->with('error', 'Você não está habilitado para fazer esta ação.');
                    };
                }
            } else {
                Auth::logout();
                return redirect()->route('login')->with('status', 'Você está cadastrado, mas sua conta ainda não foi verificada por Paes. Aguarde.');
            };
        } else {
            return redirect('login');
        }
    }
}
