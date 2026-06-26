<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\JsonResponse;

class RoleController extends Controller
{
    public function index(): JsonResponse
    {
        $roles = Role::with('permissions')
            ->where('name', '!=', Role::SUPER_ADMIN)
            ->get()
            ->map(fn(Role $role) => [
                'id'           => $role->id,
                'name'         => $role->name,
                'display_name' => $role->display_name,
                'description'  => $role->description,
                'permissions'  => $role->permissions->pluck('name'),
            ]);

        return response()->json($roles);
    }
}
