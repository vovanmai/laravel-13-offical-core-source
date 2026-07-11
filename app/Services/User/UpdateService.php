<?php

namespace App\Services\User;

use App\Models\Role;
use App\Models\User;

class UpdateService
{
    public function execute(int $id, array $data): array
    {
        $target = User::with('roles')->findOrFail($id);

        $target->update(array_filter([
            'name'     => $data['name'] ?? null,
            'email'    => $data['email'] ?? null,
            'password' => $data['password'] ?? null,
        ], fn($value) => !is_null($value)));

        if (isset($data['role_id'])) {
            $role = Role::findOrFail((int) $data['role_id']);

            if ($role->is_default) {
                $hasDefaultRoleUser = User::whereHas('roles', fn($q) => $q->where('is_default', true))
                    ->where('id', '!=', $target->id)
                    ->exists();

                abort_if($hasDefaultRoleUser, 403, 'Chỉ được phép có 1 user sở hữu vai trò mặc định.');
            }

            $target->syncRoles([$role]);
        }

        return [
            'id'    => $target->id,
            'name'  => $target->name,
            'email' => $target->email,
            'role'  => $target->role?->name,
        ];
    }
}
