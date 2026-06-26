<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\User\ListService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(private readonly ListService $listService) {}

    public function index(Request $request): JsonResponse
    {
        return response()->json(
            $this->listService->execute($request->only('search', 'role_id', 'per_page'))
        );
    }
}
