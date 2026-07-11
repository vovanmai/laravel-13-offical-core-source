<?php

namespace App\Services\Role;

use App\Enums\RoleName;
use App\Models\Role;

class ListService
{
    public function execute(array $filters = []): array
    {
        return Role::where('name', '!=', RoleName::SUPER_ADMIN->value)
            ->when(isset($filters['name']), fn($q) =>
                $q->where('name', 'like', "%{$filters['name']}%")
            )
            ->when(isset($filters['is_default']), fn($q) =>
                $q->where('is_default', filter_var($filters['is_default'], FILTER_VALIDATE_BOOLEAN))
            )
            ->orderBy('id')
            ->get()
            ->map(fn(Role $role) => [
                'id'   => $role->id,
                'name' => $role->name,
                'is_default' => $role->is_default,
                'description' => $role->description,
            ])
            ->toArray();
    }
}
