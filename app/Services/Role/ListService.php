<?php

namespace App\Services\Role;

use App\Models\Role;

class ListService
{
    public function execute(): array
    {
        return Role::where('name', '!=', Role::SUPER_ADMIN)
            ->get()
            ->map(fn(Role $role) => [
                'id'   => $role->id,
                'name' => $role->name,
            ])
            ->toArray();
    }
}
