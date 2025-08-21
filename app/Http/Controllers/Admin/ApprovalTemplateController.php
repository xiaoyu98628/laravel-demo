<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Services\ApprovalTemplateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApprovalTemplateController extends Controller
{
    public function __construct(
        private readonly ApprovalTemplateService $service,
    ) {}

    /**
     * 列表
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        return ResponseHelper::success($this->service->index($request->all()));
    }
}
