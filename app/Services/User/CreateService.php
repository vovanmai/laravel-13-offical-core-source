<?php

namespace App\Services\User;

use App\Enums\UserStatus;
use App\Mail\UserCredentialsMail;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CreateService
{
    public function execute(array $data): array
    {
        $role = Role::findOrFail((int) $data['role_id']);

        if ($role->is_default) {
            $hasDefaultRoleUser = User::whereHas('roles', fn($q) => $q->where('is_default', true))->exists();

            abort_if($hasDefaultRoleUser, 400, 'Chỉ được phép có 1 user sở hữu vai trò mặc định.');
        }

        $password = Str::password(12);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => $password,
            'status'   => UserStatus::ACTIVE,
        ]);

        $user->syncRoles([$role]);

        Mail::to($user->email)->queue(new UserCredentialsMail($user, $password));

        return [
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'role'  => $user->role?->name,
        ];
    }
}
