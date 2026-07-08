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

        $user = User::firstOrCreate(['email' => 'test@example.com'], [
            'name'     => 'Test User',
            'password' => bcrypt('password'),
        ]);
        $user->syncRoles([Role::SUPER_ADMIN]);

        User::factory(100)->make()->each(function (User $user) {
            $user->save();
            $user->syncRoles([Role::ADMIN]);
        });
    }
}
