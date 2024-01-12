<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BackOfficeMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // if user has back-office role
        if ($request->user()->hasRole('back-office')) {
            return $next($request);
        }

        // if user does not have back-office role
        return abort(403, 'Unauthorized');
    }
}