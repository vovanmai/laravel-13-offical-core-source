<?php

namespace App\Services\User;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ListService
{
    public function execute(array $filters = []): array
    {
        /** @var User|null $actor */
        $actor = Auth::user();
        $canDelete = $actor?->hasPermissionTo(\App\Models\Permission::USER_DELETE) ?? false;
        $canEdit = $actor?->hasPermissionTo(\App\Models\Permission::USER_EDIT) ?? false;

        $users = User::with('roles')
            ->whereHas('roles', fn($q) => $q->where('name', '!=', Role::SUPER_ADMIN))
            ->where('id', '!=', $actor?->id)
            ->when(isset($filters['email']), fn($q) =>
                $q->where('email', 'like', "%{$filters['email']}%")
            )
            ->when(isset($filters['role_id']), fn($q) =>
                $q->whereHas('roles', fn($rq) => $rq->where('roles.id', $filters['role_id']))
            )
            ->latest()
            ->paginate($filters['per_page'] ?? 30);

        return [
            'data' => $users->map(fn(User $user) => [
                'id'         => $user->id,
                'name'       => $user->name,
                'email'      => $user->email,
                'role'       => $user->role?->only(['id', 'name']),
                'can_delete' => $canDelete,
                'can_edit'   => $canEdit,
            ]),
            'meta' => [
                'current_page' => $users->currentPage(),
                'per_page'     => $users->perPage(),
                'total'        => $users->total(),
                'last_page'    => $users->lastPage(),
            ],
        ];
    }
}
