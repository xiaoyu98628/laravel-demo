<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\FlowRepositories;
use Illuminate\Http\JsonResponse;
use Service\Common\Library\Response\ApiResponse;

readonly class FlowService
{
    public function __construct(
        private FlowRepositories $repositories,
    ) {}

    public function create(string $type, array $inputs): JsonResponse
    {
        try {
            return ApiResponse::success();
        } catch (\Exception $e) {
            return ApiResponse::fail(message: $e->getMessage());
        }

    }
}
