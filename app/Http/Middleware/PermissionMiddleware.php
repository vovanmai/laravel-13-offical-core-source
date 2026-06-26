<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    /**
     * @param  Closure(Request): (Response)  $next
     * @param  string  ...$permissions  One or more permission names (OR logic)
     */
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        if (!$request->user()) {
            abort(401);
        }

        foreach ($permissions as $permission) {
            if (Gate::allows($permission)) {
                return $next($request);
            }
        }

        abort(403, 'Không có quyền thực hiện tác vụ này.');
    }
}
