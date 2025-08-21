<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Resources\ApprovalTemplateResources;
use App\Repositories\ApprovalTemplateRepositories;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Service\Common\Base\BaseCollection;
use Service\Common\Library\Response\ApiResponse;

readonly class ApprovalTemplateService
{
    public function __construct(
        private ApprovalTemplateRepositories $repositories,
        private ApprovalNodeTemplateService $approvalNodeTemplateService,
    ) {}

    /**
     * 列表
     * @param  array  $inputs
     * @return JsonResponse
     */
    public function index(array $inputs): JsonResponse
    {
        return empty($inputs['not_page'])
            ? ApiResponse::success(ApprovalTemplateResources::collection($this->repositories->list($inputs)))
            : ApiResponse::success(new BaseCollection($this->repositories->page($inputs), ApprovalTemplateResources::class, true));
    }

    public function store(array $inputs): JsonResponse
    {
        ! empty($inputs['callback']) && ! is_array($inputs['callback']) && $inputs['callback'] = json_decode($inputs['callback'], true);

        try {
            $model = $this->repositories->create($inputs);

            $this->updateOrStoreNodes($model->id, Arr::get($inputs, 'nodes', []));

            return ApiResponse::success($model);
        } catch (\Exception $e) {
            return ApiResponse::fail(message: $e->getMessage());
        }

    }

    private function updateOrStoreNodes(string $templateId, array $nodes, int $stepOrder = 1, ?array $idArr = []): array
    {
        return Arr::map($nodes, function ($node) use ($templateId, $stepOrder) {
            $id      = $this->approvalNodeTemplateService->updateOrStore($templateId, $node, $stepOrder);
            $idArr[] = $id;
        });
    }
}
