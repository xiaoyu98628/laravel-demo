<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Flow\FlowRequest;
use App\Services\FlowService;
use Illuminate\Http\JsonResponse;

class FlowController extends Controller
{
    public function __construct(
        private readonly FlowService $service,
    ) {}

    /**
     * 创建审批流程
     * @param  string  $type
     * @param  FlowRequest  $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function create(string $type, FlowRequest $request)
    {
        return $this->service->create($type, $request->all());
    }

    /**
     * 提交审批流程
     * @param  string  $type
     * @param  FlowRequest  $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function submit(string $type, FlowRequest $request) {}
}
