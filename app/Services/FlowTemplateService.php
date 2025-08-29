<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Resources\FlowTemplateResources;
use App\Models\FlowTemplate;
use App\Repositories\FlowTemplateRepositories;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Service\Common\Base\BaseCollection;
use Service\Common\Library\Response\ApiResponse;

readonly class FlowTemplateService
{
    public function __construct(
        private FlowTemplateRepositories $repositories,
        private FlowNodeTemplateService $flowNodeTemplateService,
    ) {}

    /**
     * 列表
     * @param  array  $inputs
     * @return JsonResponse
     */
    public function index(array $inputs): JsonResponse
    {
        return empty($inputs['not_page'])
            ? ApiResponse::success(new BaseCollection($this->repositories->page($inputs), FlowTemplateResources::class, true))
            : ApiResponse::success(FlowTemplateResources::collection($this->repositories->list($inputs)));
    }

    /**
     * 创建
     * @param  array  $inputs
     * @return JsonResponse
     * @throws \Throwable
     */
    public function store(array $inputs): JsonResponse
    {
        DB::beginTransaction();
        try {
            /** @var FlowTemplate $model */
            $model = $this->repositories->store($inputs);

            $this->flowNodeTemplateService->handleNodeTemplateTree($model->id, Arr::get($inputs, 'node_template', []));

            DB::commit();

            return ApiResponse::success($model);
        } catch (\Exception $e) {
            DB::rollBack();

            return ApiResponse::fail(message: $e->getMessage());
        }
    }

    /**
     * 详情
     * @param  string  $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $model = $this->repositories->query()
            ->where('id', $id)
            ->with([
                'nodeTemplate' => fn ($query) => $query->whereNull('parent_id')->with('children'),
            ])->first();

        if (empty($model)) {
            ApiResponse::fail(message: '数据不存在');
        }

        return ApiResponse::success(new FlowTemplateResources($model));
    }

    /**
     * 更新
     * @param  string  $id
     * @param  array  $inputs
     * @return JsonResponse
     * @throws \Throwable
     */
    public function update(string $id, array $inputs): JsonResponse
    {
        DB::beginTransaction();
        try {

            $this->repositories->update($id, $inputs);

            $this->flowNodeTemplateService->handleNodeTemplateTree($id, Arr::get($inputs, 'node_template', []));

            DB::commit();

            return ApiResponse::success();
        } catch (\Exception $e) {
            DB::rollBack();

            return ApiResponse::fail(message: $e->getMessage());
        }
    }

    /**
     * 状态
     * @param  string  $id
     * @param  array  $inputs
     * @return bool
     */
    public function status(string $id, array $inputs): bool
    {
        /** @var FlowTemplate $model */
        $model = $this->repositories->query()->where('id', $id)->first();

        if (empty($model)) {
            ApiResponse::fail(message: '数据不存在');
        }

        // 如果修改的状态与当前状态一致
        if ($inputs['status'] == $model->status) {
            ApiResponse::fail(message: '不能改为当前状态');
        }

        return $model->update(['status' => $inputs['status']]);
    }
}
