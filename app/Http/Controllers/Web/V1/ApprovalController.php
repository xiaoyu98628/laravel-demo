<?php

namespace App\Http\Controllers\Web\V1;

use App\Http\Controllers\Controller;
use App\Services\ApprovalService;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function __construct(
        private readonly ApprovalService $service,
    ) {}

    public function create(string $type, Request $request)
    {
        return $this->service->create($type, $request->all());
    }
}
