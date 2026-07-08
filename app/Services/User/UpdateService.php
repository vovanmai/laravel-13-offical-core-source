<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UpdateService
{
    public function execute(int $id, array $data): array
    {
        $actor = User::with('roles')->findOrFail(Auth::id());
        $target = User::with('roles')->findOrFail($id);

        abort_if(
            ($actor->role?->rank() ?? 0) < ($target->role?->rank() ?? 0),
            403,
            'Không đủ quyền cập nhật user có cấp bậc cao hơn.'
        );

        $target->update(array_filter([
            'name'     => $data['name'] ?? null,
            'email'    => $data['email'] ?? null,
            'password' => $data['password'] ?? null,
        ], fn($value) => !is_null($value)));

        if (isset($data['role_id'])) {
            $target->syncRoles([$data['role_id']]);
        }

        return [
            'id'    => $target->id,
            'name'  => $target->name,
            'email' => $target->email,
            'role'  => $target->role?->name,
        ];
    }
}
