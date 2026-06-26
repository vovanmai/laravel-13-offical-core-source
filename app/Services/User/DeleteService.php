<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DeleteService
{
    public function execute(User $user): void
    {
        abort_if($user->id === Auth::id(), 422, 'Không thể xóa chính mình.');

        $user->delete();
    }
}
