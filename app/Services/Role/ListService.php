<?php

namespace App\Services\Role;

use App\Enums\RoleName;
use App\Models\Role;

class ListService
{
    public function execute(): array
    {
        return Role::where('name', '!=', RoleName::SUPER_ADMIN->value)
            ->get()
            ->map(fn(Role $role) => [
                'id'   => $role->id,
                'name' => $role->name,
                'is_default' => $role->is_default,
            ])
            ->toArray();
    }
}
