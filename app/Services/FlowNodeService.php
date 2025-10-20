<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\FlowNodeRepositories;
use Illuminate\Http\JsonResponse;
use Service\Common\Library\Response\ApiResponse;

readonly class FlowNodeService
{
    public function __construct(
        private FlowNodeRepositories $repositories,
    ) {}
}
