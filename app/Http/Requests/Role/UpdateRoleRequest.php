<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'              => ['sometimes', 'string', 'max:255', 'unique:roles,name,' . $this->route('id')],
            'description'       => ['nullable', 'string', 'max:255'],
            'permission_ids'    => ['sometimes', 'array'],
            'permission_ids.*'  => ['integer', 'exists:permissions,id'],
        ];
    }
}
