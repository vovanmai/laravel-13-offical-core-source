<?php

namespace App\Services\User;

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
