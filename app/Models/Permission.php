<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    const USER_VIEW   = 'user.view';
    const USER_CREATE = 'user.create';
    const USER_EDIT   = 'user.edit';
    const USER_DELETE = 'user.delete';

    const ROLE_VIEW   = 'role.view';
    const ROLE_CREATE = 'role.create';
    const ROLE_EDIT   = 'role.edit';
    const ROLE_DELETE = 'role.delete';

    protected $fillable = ['name', 'display_name', 'description'];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_permission');
    }
}
