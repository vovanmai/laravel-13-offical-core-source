<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
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

    public function rank(): int
    {
        return self::HIERARCHY[$this->name] ?? 0;
    }

    public function isHigherThan(Role $other): bool
    {
        return $this->rank() > $other->rank();
    }
}
