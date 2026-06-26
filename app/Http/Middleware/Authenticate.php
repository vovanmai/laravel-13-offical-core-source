<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    protected function redirectTo(Request $request): ?string
    {
        // Return null for API routes → throws AuthenticationException as JSON 401
        // instead of trying to redirect to route('login')
        return $request->is('api/*') ? null : route('login');
    }
}
