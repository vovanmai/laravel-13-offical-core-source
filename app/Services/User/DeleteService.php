<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DeleteService
{
    public function execute(int $id): void
    {
        abort_if((int) Auth::id() === $id, 422, 'Không thể xóa chính mình.');

        $target = User::findOrFail($id);
        $target->delete();
    }
}
