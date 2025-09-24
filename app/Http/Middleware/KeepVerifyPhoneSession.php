<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class KeepVerifyPhoneSession
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->route()->getName() === 'sms.verify' && session('verify_phone')) {
            session(['verify_phone' => session('verify_phone')]);
        }
        
        return $next($request);
    }
}