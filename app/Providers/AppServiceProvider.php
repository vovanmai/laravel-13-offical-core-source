<?php

namespace App\Providers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->registerGates();
    }

    private function registerGates(): void
    {
        Gate::before(function (User $user, string $ability) {
            if ($user->hasRole(Role::SUPER_ADMIN)) {
                return true;
            }
        });

        // Dynamically register a gate for each permission in DB
        try {
            Permission::all()->each(function (Permission $permission) {
                Gate::define($permission->name, function (User $user) use ($permission) {
                    return $user->hasPermission($permission->name);
                });
            });
        } catch (\Exception) {
            // Silently skip if DB is not ready (e.g. during migrations)
        }
    }
}
