<?php

namespace App\Services\Role;

use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class ListService
{
    public function execute(array $filters = []): array
    {
        $actorRank = Auth::user()?->role?->rank() ?? 0;

        return Role::where('name', '!=', Role::SUPER_ADMIN)
            ->when($filters['less_than'] ?? false, function ($q) use ($actorRank) {
                $allowedNames = collect(Role::HIERARCHY)
                    ->filter(fn(int $rank) => $rank < $actorRank)
                    ->keys()
                    ->toArray();
                $q->whereIn('name', $allowedNames);
            })
            ->get()
            ->map(fn(Role $role) => [
                'id'   => $role->id,
                'name' => $role->name,
            ])
            ->toArray();
    }
}
