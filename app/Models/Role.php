<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    const SUPER_ADMIN = 'super_admin';
    const ADMIN       = 'admin';
    const SUB_ADMIN   = 'sub_admin';

    // Higher value = higher rank
    const HIERARCHY = [
        self::SUPER_ADMIN => 3,
        self::ADMIN       => 2,
        self::SUB_ADMIN   => 1,
    ];

    protected $fillable = ['name', 'display_name', 'description'];

    public function rank(): int
    {
        return self::HIERARCHY[$this->name] ?? 0;
    }

    public function isHigherThan(Role $other): bool
    {
        return $this->rank() > $other->rank();
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function hasPermission(string $permission): bool
    {
        return $this->permissions->contains('name', $permission);
    }
}
