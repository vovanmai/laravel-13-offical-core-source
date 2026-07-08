<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DeleteService
{
    public function execute(int $id): void
    {
        $actor = User::with('roles')->findOrFail(Auth::id());
        $target = User::with('roles')->findOrFail($id);

        abort_if($actor->id === $target->id, 422, 'Không thể xóa chính mình.');

        abort_unless(
            $actor->role?->isHigherThan($target->role),
            403,
            'Không đủ quyền xóa user có cấp bậc cao hơn hoặc ngang bằng.'
        );

        $target->delete();
    }
}
