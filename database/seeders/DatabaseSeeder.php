<?php

namespace Database\Seeders;

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

        $roles = Role::pluck('id', 'name');

        User::firstOrCreate(['email' => 'test@example.com'], [
            'name'    => 'Test User',
            'role_id' => $roles[Role::SUPER_ADMIN],
            'password' => bcrypt('password'),
        ]);

        $randomRoleIds = Role::whereIn('name', [Role::ADMIN, Role::SUB_ADMIN])->pluck('id');

        User::factory(100)->make()->each(function (User $user) use ($randomRoleIds) {
            $user->role_id = $randomRoleIds->random();
            $user->save();
        });
    }
}
