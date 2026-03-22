<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Step 1: Check if the user is logged in
        // We check 'id' because that's what we store during login
        // Your original code checked 'urs_id' which was a typo — it never matched!
        if (!Session::has('id') || !Session::has('user_role')) {
            return redirect()->route('login')
                ->with('errorMessage', 'Please log in first.');
        }

        // Step 2: Check if their role matches the required role for this route
        // Example: middleware('role:admin') → $role = 'admin'
        if (Session::get('user_role') !== $role) {
            return redirect()->route('login')
                ->with('errorMessage', 'You do not have permission to access this page.');
        }

        // Step 3: Pass the request to the next step (the actual page/controller)
        $response = $next($request);

        // Step 4: Add security headers to prevent browser from caching
        // admin pages — so sensitive data isn't stored in browser history
        if (method_exists($response, 'header')) {
            return $response
                ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', 'Sat, 01 Jan 1990 00:00:00 GMT');
        }

        return $response;
    }
}

?>