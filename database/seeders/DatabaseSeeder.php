<?php

namespace Database\Seeders;

use App\Enums\RoleName;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolePermissionSeeder::class);

        $superAdminRoleId = Role::where('name', RoleName::SUPER_ADMIN->value)->value('id');
        $user = User::firstOrCreate(['email' => 'superadmin@example.com'], [
            'name'     => 'Super Admin',
            'password' => bcrypt('11111111'),
            'password_changed_at' => now(),
        ]);
        $user->syncRoles([$superAdminRoleId]);

        $adminRoleId = Role::where('name', RoleName::ADMIN->value)->value('id');
        $admin = User::firstOrCreate(['email' => 'admin@example.com'], [
            'name'     => 'Admin',
            'password' => bcrypt('11111111'),
            'password_changed_at' => now(),
        ]);
        $admin->syncRoles([$adminRoleId]);
    }
}
