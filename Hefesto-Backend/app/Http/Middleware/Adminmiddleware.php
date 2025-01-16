<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Adminmiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            if (Auth::user()->rol == "administrador") { 
                return $next($request);
            } else {
                return response()->json(['message' => 'Forbidden: You do not have the required admin role.'], 403);
            }
        } else {
            return response()->json(['message' => 'Unauthorized: You are not authenticated.'], 401);
        }
    }




}
