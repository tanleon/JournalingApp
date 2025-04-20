<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\Log;

class AutoLogoutMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            //Log::info('AutoLogout middleware called'); 

            if (!session()->has('last_activity')) {
                session(['last_activity' => now()]);
            }

            $lastActivity = session('last_activity');
            //Log::info('Last activity: ' . $lastActivity); // Log the last activity timestamp

            $timeout = config('session.lifetime') * 60; // Convert session lifetime to seconds
            //Log::info('Session timeout value: ' . $timeout);
            //Log::info('Session lifetime from config: ' . config('session.lifetime'));
            if (now()->diffInSeconds($lastActivity) > $timeout) {
                Auth::logout();
                session()->flush();
                return redirect()->route('login')->with('info', 'You have been logged out due to inactivity.');
            }

            session(['last_activity' => now()]); // Update last activity timestamp
        }

        return $next($request);
    }
}