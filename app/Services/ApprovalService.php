<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\JsonResponse;
use Service\Common\Library\Response\ApiResponse;

class ApprovalService
{
    public function __construct() {}

    public function create(string $type, array $inputs): JsonResponse
    {
        try {
            return ApiResponse::success();
        } catch (\Exception $e) {
            return ApiResponse::fail(message: $e->getMessage());
        }

    }
}
