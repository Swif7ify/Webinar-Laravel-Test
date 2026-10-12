<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->user()->role !== $role) {
            // Redirect user to their appropriate dashboard based on actual role
            $userRole = auth()->user()->role;
            
            if ($userRole === 'teacher') {
                return redirect()->route('teacher.dashboard');
            } elseif ($userRole === 'student') {
                return redirect()->route('student.dashboard');
            }
            
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
