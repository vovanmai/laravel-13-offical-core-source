<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UpdateService
{
    public function execute(int $id, array $data): array
    {
        $actor = User::with('role')->findOrFail(Auth::id());
        $target = User::with('role')->findOrFail($id);

        abort_if(
            ($actor->role?->rank() ?? 0) < ($target->role?->rank() ?? 0),
            403,
            'Không đủ quyền cập nhật user có cấp bậc cao hơn.'
        );

        $target->update(array_filter([
            'name'     => $data['name'] ?? null,
            'email'    => $data['email'] ?? null,
            'password' => $data['password'] ?? null,
            'role_id'  => $data['role_id'] ?? null,
        ], fn($value) => !is_null($value)));

        $target->load('role');

        return [
            'id'    => $target->id,
            'name'  => $target->name,
            'email' => $target->email,
            'role'  => $target->role?->display_name,
        ];
    }
}
