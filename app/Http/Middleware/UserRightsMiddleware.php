<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserRightsMiddleware
{
    public function handle(Request $request, Closure $next, $right_id)
    {
        if (!_isUserAdmin() && !_isUserRightToSee($right_id)){
            abort(404);
        }

        return $next($request);
    }
}
