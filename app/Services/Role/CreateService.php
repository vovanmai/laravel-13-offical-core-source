<?php

namespace App\Services\Role;

use App\Models\Role;

class CreateService
{
    public function execute(array $data): array
    {
        $role = Role::create([
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        $role->syncPermissions($data['permission_ids'] ?? []);

        return [
            'id'          => $role->id,
            'name'        => $role->name,
            'description' => $role->description,
            'permissions' => $role->permissions->pluck('name'),
        ];
    }
}
