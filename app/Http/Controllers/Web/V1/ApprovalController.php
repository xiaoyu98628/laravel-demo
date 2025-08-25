<?php

namespace App\Http\Controllers\Web\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Approval\ApprovalRequest;
use App\Services\ApprovalService;

class ApprovalController extends Controller
{
    public function __construct(
        private readonly ApprovalService $service,
    ) {}

    public function create(string $type, ApprovalRequest $request)
    {
        return $this->service->create($type, $request->all());
    }
}
