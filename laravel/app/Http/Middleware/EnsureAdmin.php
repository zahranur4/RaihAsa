<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureAdmin
{
    /**
     * Handle an incoming request.
     * Allow access if authenticated user has is_admin attribute or email is admin@example.com.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        // Allow if there's an is_admin boolean column, or if email matches default admin
        if (isset($user->is_admin) && $user->is_admin) {
            return $next($request);
        }

        if ($user->email === 'admin@example.com') {
            return $next($request);
        }

        abort(403, 'Akses ditolak. Hanya admin yang dapat mengakses halaman ini.');
    }
}
