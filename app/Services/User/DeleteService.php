<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DeleteService
{
    public function execute(int $id): void
    {
        abort_if((int) Auth::id() === $id, 422, 'Không thể xóa chính mình.');

        $target = User::with('roles')->findOrFail($id);

        abort_if($target->roles->contains('is_default', true), 403, 'Không thể xoá người dùng có vai trò mặc định.');

        $target->delete();
    }
}
