<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SessionTimeout
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $timeout = 10 * 60;

            $lastActivity = session('lastActivityTime');
            $currentTime = time();

            if ($lastActivity && ($currentTime - $lastActivity) > $timeout) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect('/login')->with('message', 'Vous avez été déconnecté en raison d\'une inactivité prolongée.');
            }

            session(['lastActivityTime' => $currentTime]);
        }

        return $next($request);
    }
}
