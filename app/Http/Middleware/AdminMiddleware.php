<?php
// new update -> Arash-abraham

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::check()) {
            if(auth()->user()->role == 'admin') {
                return $next($request); // only for users with admin role 
            }
            return abort(404); // else ['not found']
        }
        return to_route('login');   
    }
}
