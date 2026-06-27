<?php

namespace App\Http\Requests\User;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'name'     => ['sometimes', 'string', 'max:255'],
            'email'    => ['sometimes', 'email', 'unique:users,email,' . $this->route('id')],
            'password' => ['sometimes', 'string', 'min:8'],
            'role_id'  => ['sometimes', 'integer', Rule::in($this->allowedRoleIds())],
        ];
    }
}
