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
            ['name' => 'user.view',   'display_name' => 'Xem người dùng'],
            ['name' => 'user.create', 'display_name' => 'Tạo người dùng'],
            ['name' => 'user.edit',   'display_name' => 'Sửa người dùng'],
            ['name' => 'user.delete', 'display_name' => 'Xóa người dùng'],
            ['name' => 'role.view',   'display_name' => 'Xem vai trò'],
            ['name' => 'role.create', 'display_name' => 'Tạo vai trò'],
            ['name' => 'role.edit',   'display_name' => 'Sửa vai trò'],
            ['name' => 'role.delete', 'display_name' => 'Xóa vai trò'],
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm['name']], $perm);
        }

        $superAdmin = Role::firstOrCreate(['name' => Role::SUPER_ADMIN], [
            'display_name' => 'Super Admin',
            'description'  => 'Toàn quyền hệ thống',
        ]);
        $superAdmin->permissions()->sync(Permission::pluck('id'));

        $admin = Role::firstOrCreate(['name' => Role::ADMIN], [
            'display_name' => 'Admin',
            'description'  => 'Quản trị người dùng và vai trò',
        ]);
        $admin->permissions()->sync(
            Permission::whereIn('name', ['user.view', 'user.create', 'user.edit', 'role.view'])->pluck('id')
        );

        $subAdmin = Role::firstOrCreate(['name' => Role::SUB_ADMIN], [
            'display_name' => 'Sub Admin',
            'description'  => 'Quyền hạn chế',
        ]);
        $subAdmin->permissions()->sync(
            Permission::whereIn('name', ['user.view', 'role.view'])->pluck('id')
        );
    }
}
