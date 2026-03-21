<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $roles): Response
    {
        // 1. Get role from session (lowercase)
        $userRole = $request->session()->get('user_role', '');

        // 2. Not logged in?
        if (empty($userRole)) {
            return redirect('/login')->with('error', 'Please log in.');
        }

        // 3. ADMIN can access everything
        if ($userRole === 'admin') {
            return $next($request);
        }

        // 4. Check if the current user role is allowed for this specific route
        $allowedRoles = explode('|', $roles); // expects 'teacher' or 'admin|teacher'
        
        if (in_array($userRole, $allowedRoles)) {
            return $next($request);
        }

        // 5. If a Teacher tries to go to an Admin page, send them back to TeacherUI
        if ($userRole === 'Teacher') {
            return redirect()->route('Teacher.TeacherUI')->with('error', 'Access Denied.');
        }

        return redirect('/');
    }
}