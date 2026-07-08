<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::with('roles.permissions')
            ->where('email', $request->email)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Email hoặc mật khẩu không đúng. Vui lòng liên hệ Admin để cấp lại tài khoản.',
            ], 403);
        }

        if ($user->status === UserStatus::SUSPENDED) {
            return response()->json([
                'message' => 'Tài khoản đã bị khóa. Vui lòng liên hệ Admin.',
            ], 403);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'user'  => [
                'id'          => $user->id,
                'name'        => $user->name,
                'email'       => $user->email,
                'role_name'        => $user->role?->name,
                'permissions' => $user->getAllPermissions()->pluck('name'),
                'must_change_password' => is_null($user->password_changed_at),
            ],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Đăng xuất thành công.']);
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user()->load('roles.permissions');

        return response()->json([
            'id'          => $user->id,
            'name'        => $user->name,
            'email'       => $user->email,
            'role_name'        => $user->role?->name,
            'permissions' => $user->getAllPermissions()->pluck('name'),
            'must_change_password' => is_null($user->password_changed_at),
        ]);
    }

    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $request->user()->update([
            'password'             => $request->password,
            'password_changed_at'  => now(),
        ]);

        return response()->json(['message' => 'Đổi mật khẩu thành công.']);
    }
}
