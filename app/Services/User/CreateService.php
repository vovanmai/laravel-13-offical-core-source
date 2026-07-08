<?php

namespace App\Services\User;

use App\Enums\UserStatus;
use App\Mail\UserCredentialsMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CreateService
{
    public function execute(array $data): array
    {
        $password = Str::password(12);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => $password,
            'status'   => UserStatus::ACTIVE,
        ]);

        $user->syncRoles([$data['role_id']]);

        Mail::to($user->email)->queue(new UserCredentialsMail($user, $password));

        return [
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'role'  => $user->role?->name,
        ];
    }
}
