<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListService
{
    public function execute(array $filters = []): array
    {
        $users = User::with('role')
            ->when(isset($filters['search']), fn($q) =>
                $q->where(fn($q) =>
                    $q->where('name', 'like', "%{$filters['search']}%")
                      ->orWhere('email', 'like', "%{$filters['search']}%")
                )
            )
            ->when(isset($filters['role_id']), fn($q) =>
                $q->where('role_id', $filters['role_id'])
            )
            ->latest()
            ->paginate($filters['per_page'] ?? 30);

        return [
            'data' => $users->map(fn(User $user) => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role?->display_name,
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
