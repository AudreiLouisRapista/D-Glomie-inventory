<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BranchMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // SuperAdmin sees all branches — no filter injected
        if (session('user_role') === 'SuperAdmin') {
            return $next($request);
        }

        // Inject branch_id into every request from server-side session
        // User cannot manipulate this — it comes from session, not input
        $request->merge(['branch_id' => session('branch_id')]);

        return $next($request);
    }
}