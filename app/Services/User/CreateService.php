<?php

namespace App\Services\User;

use App\Models\User;

class CreateService
{
    public function execute(array $data): array
    {
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => $data['password'],
        ]);

        $user->syncRoles([$data['role_id']]);

        return [
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'role'  => $user->role?->display_name,
        ];
    }
}
