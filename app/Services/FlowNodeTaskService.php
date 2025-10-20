<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\FlowNodeTaskRepositories;
use Illuminate\Http\JsonResponse;
use Service\Common\Library\Response\ApiResponse;

readonly class FlowNodeTaskService
{
    public function __construct(
        private FlowNodeTaskRepositories $repositories,
    ) {}

    /**
     * 审批
     * @param  string  $id
     * @param  array  $inputs
     * @return JsonResponse
     */
    public function approve(string $id, array $inputs): JsonResponse
    {
        try {
            return ApiResponse::success();
        } catch (\Exception $e) {
            return ApiResponse::fail(message: $e->getMessage());
        }
    }
}
