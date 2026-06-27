<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UpdateService
{
    public function execute(User $user, array $data): array
    {
        $actor = User::with('role')->findOrFail(Auth::id());
        $target = $user->loadMissing('role');

        abort_if(
            ($actor->role?->rank() ?? 0) < ($target->role?->rank() ?? 0),
            403,
            'Không đủ quyền cập nhật user có cấp bậc cao hơn.'
        );

        $user->update(array_filter([
            'name'     => $data['name'] ?? null,
            'email'    => $data['email'] ?? null,
            'password' => $data['password'] ?? null,
            'role_id'  => $data['role_id'] ?? null,
        ], fn($value) => !is_null($value)));

        $user->load('role');

        return [
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'role'  => $user->role?->display_name,
        ];
    }
}
