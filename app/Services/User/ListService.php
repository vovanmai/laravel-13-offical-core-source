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
        $actorRank = $actor?->role?->rank() ?? 0;
        $canDelete = $actor?->hasPermission(\App\Models\Permission::USER_DELETE) ?? false;
        $canEdit = $actor?->hasPermission(\App\Models\Permission::USER_EDIT) ?? false;

        $users = User::with('role')
            ->whereHas('role', fn($q) => $q->where('name', '!=', Role::SUPER_ADMIN))
            ->where('id', '!=', $actor?->id)
            ->when(isset($filters['email']), fn($q) =>
                $q->where('email', 'like', "%{$filters['email']}%")
            )
            ->when(isset($filters['role_id']), fn($q) =>
                $q->where('role_id', $filters['role_id'])
            )
            ->latest()
            ->paginate($filters['per_page'] ?? 30);

        return [
            'data' => $users->map(fn(User $user) => [
                'id'         => $user->id,
                'name'       => $user->name,
                'email'      => $user->email,
                'role'       => $user->role?->only(['id', 'name', 'display_name']),
                'can_delete' => $canDelete && $actorRank > ($user->role?->rank() ?? 0),
                'can_edit' => $canEdit && $actorRank > ($user->role?->rank() ?? 0),
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
