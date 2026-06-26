<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DeleteService
{
    public function execute(User $user): void
    {
        $actor = User::with('role')->findOrFail(Auth::id());
        $target = $user->loadMissing('role');

        abort_if($actor->id === $target->id, 422, 'Không thể xóa chính mình.');

        abort_unless(
            $actor->role?->isHigherThan($target->role),
            403,
            'Không đủ quyền xóa user có cấp bậc cao hơn hoặc ngang bằng.'
        );

        $user->delete();
    }
}
