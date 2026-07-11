<?php

namespace App\Services\User;

use App\Enums\RoleName;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ListService
{
    public function execute(array $filters = []): array
    {
        /** @var User|null $actor */
        $actor = Auth::user();

        $users = User::with('roles')
            ->whereHas('roles', fn($q) => $q->where('name', '!=', RoleName::SUPER_ADMIN->value))
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
                'role'       => $user->role?->only(['id', 'name', 'is_default']),
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
