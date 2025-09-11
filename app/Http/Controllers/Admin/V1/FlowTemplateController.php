<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\FlowTemplate\FlowTemplateDivRequest;
use App\Http\Requests\FlowTemplate\FlowTemplateRequest;
use App\Services\FlowTemplateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Service\Common\Library\Tools\Tools;

class FlowTemplateController extends Controller
{
    public function __construct(
        private readonly FlowTemplateService $service,
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

    /**
     * 创建
     * @param  FlowTemplateRequest  $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function store(FlowTemplateRequest $request)
    {
        return $this->service->store($request->all());
    }

    /**
     * 详情
     * @param  string  $id
     * @return null
     */
    public function show(string $id)
    {
        return $this->service->show($id);
    }

    /**
     * 更新
     * @param  string  $id
     * @param  FlowTemplateRequest  $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function update(string $id, FlowTemplateRequest $request)
    {
        return $this->service->update($id, $request->all());
    }

    /**
     * 状态
     * @param  string  $id
     * @param  FlowTemplateDivRequest  $request
     * @return JsonResponse
     */
    public function status(string $id, FlowTemplateDivRequest $request)
    {
        return $this->service->status($id, $request->all());
    }
}
