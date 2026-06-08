<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Session::has('id') || !Session::has('user_role')) {
            return redirect()->route('login')
                ->with('errorMessage', 'Please log in first.');
        }

        // ← check if user role is in the allowed roles list
        if (!in_array(Session::get('user_role'), $roles)) {
            return redirect()->route('login')
                ->with('errorMessage', 'You do not have permission to access this page.');
        }

        $response = $next($request);

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