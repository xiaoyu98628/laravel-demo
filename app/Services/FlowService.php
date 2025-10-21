<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Service\Common\Library\Response\ApiResponse;

readonly class FlowService
{
    public function __construct(
    ) {}

    /**
     * 创建审批流程
     * @param  string  $type
     * @param  array  $inputs
     * @return JsonResponse
     * @throws \Throwable
     */
    public function create(string $type, array $inputs): JsonResponse
    {
        DB::beginTransaction();
        try {

            DB::commit();

            return ApiResponse::success();
        } catch (\Exception $e) {
            DB::rollBack();

            return ApiResponse::fail(message: $e->getMessage());
        }
    }
}
