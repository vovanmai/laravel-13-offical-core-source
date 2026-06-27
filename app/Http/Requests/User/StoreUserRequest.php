<?php

namespace App\Http\Requests\User;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    private function allowedRoleIds(): array
    {
        $actorRank = $this->user()->role?->rank() ?? 0;

        return Role::all()
            ->filter(fn(Role $role) => (Role::HIERARCHY[$role->name] ?? 0) < $actorRank)
            ->pluck('id')
            ->toArray();
    }

    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required', 'string'],
            'role_id'  => ['required', 'integer', Rule::in($this->allowedRoleIds())],
        ];
    }
}
