<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Services\Role\CreateService;
use App\Services\Role\ListService;
use App\Services\Role\ShowService;
use App\Services\Role\UpdateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct(
        private readonly ListService   $listService,
        private readonly ShowService   $showService,
        private readonly CreateService $createService,
        private readonly UpdateService $updateService,
    ) {}

    public function index(Request $request): JsonResponse
    {
        return response()->json($this->listService->execute($request->only('name', 'is_default')));
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->showService->execute($id));
    }

    public function store(StoreRoleRequest $request): JsonResponse
    {
        return response()->json(
            $this->createService->execute($request->validated()),
            201
        );
    }

    public function update(UpdateRoleRequest $request, int $id): JsonResponse
    {
        return response()->json(
            $this->updateService->execute($id, $request->validated())
        );
    }
}
