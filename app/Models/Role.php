<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    const SUPER_ADMIN = 'super_admin';
    const ADMIN       = 'admin';

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
        ];
    }
}
