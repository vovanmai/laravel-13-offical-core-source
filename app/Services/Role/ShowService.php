<?php

namespace App\Services\Role;

use App\Models\Role;

class ShowService
{
    public function execute(int $id): array
    {
        $role = Role::findOrFail($id);

        return [
            'id'          => $role->id,
            'name'        => $role->name,
            'is_default'  => $role->is_default,
            'description' => $role->description,
            'permission_ids' => $role->permissions->pluck('id'),
        ];
    }
}
