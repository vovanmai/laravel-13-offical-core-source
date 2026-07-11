<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordChanged
{
    public function handle(Request $request, Closure $next): Response
    {
        if (is_null($request->user()->password_changed_at)) {
            return response()->json([
                'message' => 'Vui lòng đổi mật khẩu lần đầu tiên trước khi tiếp tục.',
                'data' => [
                    'need_to_change_password' => true,
                ],
            ], 403);
        }

        return $next($request);
    }
}
