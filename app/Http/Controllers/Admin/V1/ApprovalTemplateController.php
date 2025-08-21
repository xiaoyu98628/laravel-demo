<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApprovalTemplate\ApprovalTemplateRequest;
use App\Services\ApprovalTemplateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Service\Common\Library\Tools\Tools;

class ApprovalTemplateController extends Controller
{
    public function __construct(
        private readonly ApprovalTemplateService $service,
    ) {}

    /**
     * 列表
     * @param  Request  $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        return $this->service->index(Tools::arrayDefault($request->all(), [
            'page'      => 1,
            'page_size' => 20,
            'not_page'  => 0,
        ]));
    }

    public function store(ApprovalTemplateRequest $request)
    {
        return $this->service->store($request->all());
    }
}
