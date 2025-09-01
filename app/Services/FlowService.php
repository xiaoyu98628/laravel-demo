<?php

declare(strict_types=1);

namespace App\Services;

use App\Flow\Builder;
use Illuminate\Http\JsonResponse;
use Service\Common\Library\Response\ApiResponse;

readonly class FlowService
{
    public function __construct(
        private Builder $flowBuilder,
    ) {}

    /**
     * 创建审批流程
     * @param  string  $type
     * @param  array  $inputs
     * @return JsonResponse
     */
    public function create(string $type, array $inputs): JsonResponse
    {
        try {
            $this->flowBuilder->setType($type)->setInputs($inputs)->flow()->node()->task();
            return ApiResponse::success();
        } catch (\Exception $e) {
            return ApiResponse::fail(message: $e->getMessage());
        }
    }
}
