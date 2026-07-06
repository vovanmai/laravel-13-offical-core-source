<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Upload\StoreUploadRequest;
use App\Services\Upload\CreateService;
use Illuminate\Http\JsonResponse;

class UploadController extends Controller
{
    public function __construct(
        private readonly CreateService $createService,
    ) {}

    public function store(StoreUploadRequest $request): JsonResponse
    {
        return response()->json(
            $this->createService->execute($request->validated()),
            201
        );
    }
}
