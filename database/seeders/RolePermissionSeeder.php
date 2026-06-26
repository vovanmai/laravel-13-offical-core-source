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
            ['name' => Permission::USER_VIEW,   'display_name' => 'Xem người dùng'],
            ['name' => Permission::USER_CREATE, 'display_name' => 'Tạo người dùng'],
            ['name' => Permission::USER_EDIT,   'display_name' => 'Sửa người dùng'],
            ['name' => Permission::USER_DELETE, 'display_name' => 'Xóa người dùng'],
            ['name' => Permission::ROLE_VIEW,   'display_name' => 'Xem vai trò'],
            ['name' => Permission::ROLE_CREATE, 'display_name' => 'Tạo vai trò'],
            ['name' => Permission::ROLE_EDIT,   'display_name' => 'Sửa vai trò'],
            ['name' => Permission::ROLE_DELETE, 'display_name' => 'Xóa vai trò'],
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
        $admin->permissions()->sync(Permission::pluck('id'));

        $subAdmin = Role::firstOrCreate(['name' => Role::SUB_ADMIN], [
            'display_name' => 'Sub Admin',
            'description'  => 'Quyền hạn chế',
        ]);
        $subAdmin->permissions()->sync(
            Permission::whereIn('name', [Permission::USER_VIEW, Permission::ROLE_VIEW])->pluck('id')
        );
    }
}
