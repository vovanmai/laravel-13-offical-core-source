<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    const USER_VIEW   = 'user.view';
    const USER_CREATE = 'user.create';
    const USER_EDIT   = 'user.edit';
    const USER_DELETE = 'user.delete';

    const ROLE_VIEW   = 'role.view';
    const ROLE_CREATE = 'role.create';
    const ROLE_EDIT   = 'role.edit';
    const ROLE_DELETE = 'role.delete';
}
