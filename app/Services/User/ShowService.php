<?php

namespace App\Services\User;

use App\Models\User;

class ShowService
{
    public function execute(int $id): array
    {
        $user = User::with('roles')->findOrFail($id);

        return [
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'role_id'  => $user->role?->id,
        ];
    }
}
