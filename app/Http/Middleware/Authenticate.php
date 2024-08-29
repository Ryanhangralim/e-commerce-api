<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Handle unauthenticated users.
     */
    protected function unauthenticated($request, array $guards)
    {
        // If the request expects JSON, return a 401 JSON response
        if ($request->expectsJson()) {
            abort(response()->json(['message' => 'Unauthenticated.'], 401));
        }

        // Optionally, if you still have web routes, you can redirect for non-JSON requests
        return redirect()->guest(route('login'));
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // This method is now just a fallback for non-API requests
        return null;
    }
}


