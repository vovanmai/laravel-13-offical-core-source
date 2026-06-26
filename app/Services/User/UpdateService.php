<?php

namespace App\Services\User;

use App\Models\User;

class UpdateService
{
    public function execute(User $user, array $data): array
    {
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
