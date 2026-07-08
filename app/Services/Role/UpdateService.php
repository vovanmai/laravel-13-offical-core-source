<?php

namespace App\Services\Role;

use App\Models\Role;

class UpdateService
{
    public function execute(int $id, array $data): array
    {
        $role = Role::findOrFail($id);

        abort_if($role->is_default, 403, 'Không thể chỉnh sửa vai trò mặc định.');

        $role->update(array_filter([
            'name'        => $data['name'] ?? null,
            'description' => $data['description'] ?? null,
        ], fn($value) => !is_null($value)));

        if (isset($data['permission_ids'])) {
            $role->syncPermissions($data['permission_ids']);
        }

        return [
            'id'          => $role->id,
            'name'        => $role->name,
            'description' => $role->description,
            'permissions' => $role->permissions->pluck('name'),
        ];
    }
}
