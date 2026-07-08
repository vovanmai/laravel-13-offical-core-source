<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => Permission::USER_VIEW,   'group' => 'Người dùng', 'display_name' => 'Xem người dùng'],
            ['name' => Permission::USER_CREATE, 'group' => 'Người dùng', 'display_name' => 'Tạo người dùng'],
            ['name' => Permission::USER_EDIT,   'group' => 'Người dùng', 'display_name' => 'Sửa người dùng'],
            ['name' => Permission::USER_DELETE, 'group' => 'Người dùng', 'display_name' => 'Xóa người dùng'],
            ['name' => Permission::ROLE_VIEW,   'group' => 'Vai trò', 'display_name' => 'Xem vai trò'],
            ['name' => Permission::ROLE_CREATE, 'group' => 'Vai trò', 'display_name' => 'Tạo vai trò'],
            ['name' => Permission::ROLE_EDIT,   'group' => 'Vai trò', 'display_name' => 'Sửa vai trò'],
            ['name' => Permission::ROLE_DELETE, 'group' => 'Vai trò', 'display_name' => 'Xóa vai trò'],
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm['name']], $perm);
        }

        $superAdmin = Role::firstOrCreate(['name' => Role::SUPER_ADMIN], [
            'description' => 'Toàn quyền hệ thống',
        ]);
        $superAdmin->permissions()->sync(Permission::pluck('id'));

        $admin = Role::firstOrCreate(['name' => Role::ADMIN], [
            'description' => 'Quản trị người dùng và vai trò',
        ]);
        $admin->permissions()->sync(Permission::pluck('id'));

        $subAdmin = Role::firstOrCreate(['name' => Role::SUB_ADMIN], [
            'description' => 'Quyền hạn chế',
        ]);
        $subAdmin->permissions()->sync(
            Permission::whereIn('name', [Permission::USER_VIEW, Permission::USER_CREATE, Permission::ROLE_VIEW])->pluck('id')
        );
    }
}
