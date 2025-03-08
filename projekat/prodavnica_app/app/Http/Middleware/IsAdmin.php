<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         //Proverava da li je korisnik ulogovan i da li je uloga 'administrator'
         if (Auth::check() && Auth::user()->uloga === 'administrator') {
            return $next($request);
        }

        //Ako nije administrator, vraca gresku
        return response()->json(['message' => 'Nemate dozvolu za ovu akciju.'], 403);
    }
}
