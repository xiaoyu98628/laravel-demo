<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\JsonResponse;
use Service\Common\Library\Response\ApiResponse;

class ApprovalService
{
    public function __construct() {}

    private function create(): JsonResponse
    {
        return ApiResponse::success();
    }
}
