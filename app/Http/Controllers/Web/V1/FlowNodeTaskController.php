<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\FlowNodeTask\FlowNodeTaskRequest;
use App\Services\FlowNodeTaskService;

class FlowNodeTaskController extends Controller
{
    public function __construct(
        private readonly FlowNodeTaskService $service
    ) {}

    public function approve(FlowNodeTaskRequest $request, string $id)
    {
        return $this->service->approve($id, $request->all());
    }
}
