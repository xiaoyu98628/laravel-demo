<?php

declare(strict_types=1);

namespace App\Services;

use App\Builders\FlowBuilder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Service\Common\Library\Response\ApiResponse;

readonly class FlowService
{
    public function __construct(
        private FlowBuilder $flowBuilder,
    ) {}

    /**
     * 创建审批流程
     * @param  string  $code
     * @param  array  $inputs
     * @return JsonResponse
     * @throws \Throwable
     */
    public function create(string $code, array $inputs): JsonResponse
    {
        DB::beginTransaction();
        try {

            $this->flowBuilder->build($code, $inputs);

            DB::commit();

            return ApiResponse::success();
        } catch (\Exception $e) {
            DB::rollBack();

            return ApiResponse::fail(message: $e->getMessage());
        }
    }

    /**
     * 提交审批流程
     * @param  string  $id
     * @param  array  $inputs
     * @return JsonResponse
     * @throws \Throwable
     */
    public function submit(string $id, array $inputs): JsonResponse
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
