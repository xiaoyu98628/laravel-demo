<?php

namespace App\Http\Controllers\Web\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Flow\FlowRequest;
use App\Services\FlowService;

class FlowController extends Controller
{
    public function __construct(
        private readonly FlowService $service,
    ) {}

    public function create(string $type, FlowRequest $request)
    {
        return $this->service->create($type, $request->all());
    }
}
