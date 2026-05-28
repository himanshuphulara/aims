<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

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
        if ($user->isAdmin()) {
            return $next($request);
        }

        if ($user->hasPermissions($permission)) {
            return $next($request);
        }

        if ($user->can('dashboard')) {
            return redirect()->route('dashboard')->with('error', 'You do not have permission to access this resource.');
        }

        return redirect('/')->with('error', 'You do not have permission to access this resource.');
    
    }
}
