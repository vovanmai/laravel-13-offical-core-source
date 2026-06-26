<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use App\Services\User\CreateService;
use App\Services\User\DeleteService;
use App\Services\User\ListService;
use App\Services\User\UpdateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        private readonly ListService   $listService,
        private readonly CreateService $createService,
        private readonly UpdateService $updateService,
        private readonly DeleteService $deleteService,
    ) {}

    public function index(Request $request): JsonResponse
    {
        return response()->json(
            $this->listService->execute($request->only('search', 'role_id', 'per_page'))
        );
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        return response()->json(
            $this->createService->execute($request->validated()),
            201
        );
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        return response()->json(
            $this->updateService->execute($user, $request->validated())
        );
    }

    public function destroy(User $user): JsonResponse
    {
        $this->deleteService->execute($user);

        return response()->json(['message' => 'Xóa user thành công.']);
    }
}
