<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Broadcast\TestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BroadcastTestController extends Controller
{
    public function __construct(
        private readonly TestService $testService,
    ) {}

    public function test(Request $request): JsonResponse
    {
        return response()->json(
            $this->testService->execute($request->only('message'))
        );
    }
}
