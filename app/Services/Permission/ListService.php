<?php

namespace App\Services\Permission;

use App\Models\Permission;

class ListService
{
    public function execute(array $filters = []): array
    {
        return Permission::orderBy('group')
            ->orderBy('id')
            ->get()
            ->map(fn(Permission $permission) => [
                'id'           => $permission->id,
                'name'         => $permission->name,
                'display_name' => $permission->display_name,
                'group'        => $permission->group,
            ])
            ->toArray();
    }
}
