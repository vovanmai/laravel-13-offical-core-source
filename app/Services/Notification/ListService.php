<?php

namespace App\Services\Notification;

use Illuminate\Support\Facades\Auth;

class ListService
{
    public function execute(array $filters = []): array
    {
        $notifications = Auth::user()
            ->notifications()
            ->when(isset($filters['unread']) && $filters['unread'], fn($q) => $q->whereNull('read_at'))
            ->latest()
            ->paginate($filters['per_page'] ?? 30);

        return [
            'data' => $notifications->map(fn($notification) => [
                'id'         => $notification->id,
                'type'       => $notification->type,
                'data'       => $notification->data,
                'read_at'    => $notification->read_at,
                'created_at' => $notification->created_at,
            ]),
            'meta' => [
                'current_page' => $notifications->currentPage(),
                'per_page'     => $notifications->perPage(),
                'total'        => $notifications->total(),
                'last_page'    => $notifications->lastPage(),
                'unread_count' => Auth::user()->unreadNotifications()->count(),
            ],
        ];
    }
}
