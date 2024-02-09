<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserBranch
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::check() && Auth::user()->branch == null){
            Auth::guard('web')->logout();
            return redirect('/')->with([
                'level' => 'warning',
                'message' => 'Usted acaba de ser desloguedo del sistema, Contacte al Administrador para que le asigne un Nuevo Centro !'
            ]);
        }
        return $next($request);
    }
    
}

