<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Helpers\ActivityLogHelper;

class LogActivityMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (Auth::check() && in_array(strtolower($request->method()), ['post', 'put', 'patch', 'delete'])) {
            $action = strtoupper($request->method());
            $controller = optional($request->route()->getActionName());
            $model = class_basename($request->route()->getController());
            $description = 'Accessed URL: ' . $request->fullUrl();

            ActivityLogHelper::log($action, $model, $description);
        }

        return $response;
    }
}

