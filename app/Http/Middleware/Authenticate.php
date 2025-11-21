<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected function redirectTo($request)
{
    if (! $request->expectsJson()) {

        // Jika route memakai guard admin → redirect ke admin.login
        if ($request->routeIs('admin.*')) {
            return route('admin.login');
        }

        // default
        return route('login');
    }
}

}
