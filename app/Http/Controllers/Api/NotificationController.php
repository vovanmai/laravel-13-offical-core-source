<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Notification\ListService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(
        private readonly ListService $listService,
    ) {}

    public function index(Request $request): JsonResponse
    {
        return response()->json(
            $this->listService->execute($request->only('unread', 'per_page'))
        );
    }
}
