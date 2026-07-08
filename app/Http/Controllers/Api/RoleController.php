<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Role\ListService;
use Illuminate\Http\JsonResponse;

class RoleController extends Controller
{
    public function __construct(
        private readonly ListService $listService,
    ) {}

    public function index(): JsonResponse
    {
        return response()->json($this->listService->execute());
    }
}
