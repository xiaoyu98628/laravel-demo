<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\FlowNodeTemplate;
use App\Repositories\FlowNodeTemplateRepositories;
use Illuminate\Support\Arr;

readonly class FlowNodeTemplateService
{
    public function __construct(
        private FlowNodeTemplateRepositories $repositories,
    ) {}

    /**
     * 处理树形数据
     * @param  string  $flowTemplateId
     * @param  array  $nodeTemplates
     * @param  string|null  $parentId
     * @param  int  $stepOrder
     * @return array
     */
    public function handleNodeTemplateTree(string $flowTemplateId, array $nodeTemplates, ?string $parentId = null, int $stepOrder = 1): array
    {
        $idArr = [];
        foreach ($nodeTemplates as $nodeTemplate) {
            $id      = $this->upsert($flowTemplateId, $nodeTemplate, $stepOrder, $parentId);
            $idArr[] = $id;

            if (! empty($nodeTemplate['children'])) {
                $idArr = array_merge($idArr, $this->handleNodeTemplateTree($flowTemplateId, $nodeTemplate['children'], $id, $stepOrder + 1));
            }
        }

        return $idArr;
    }

    /**
     * 创建或更新
     * @param  string  $flowTemplateId
     * @param  array  $nodeTemplate
     * @param  int  $stepOrder
     * @param  string|null  $parentId
     * @return string
     */
    private function upsert(string $flowTemplateId, array $nodeTemplate, int $stepOrder, ?string $parentId = null): string
    {
        $nodeTemplate['flow_template_id'] = $flowTemplateId;
        $nodeTemplate['depth']            = $stepOrder;
        $nodeTemplate['parent_id']        = $parentId;
        $nodeTemplate['rules']            = $this->formatRules(Arr::get($nodeTemplate, 'rules', []));

        if (empty($nodeTemplate['id'])) {
            /** @var FlowNodeTemplate $model */
            $model = $this->repositories->store($nodeTemplate);

            return $model->id;
        }

        $this->repositories->update($nodeTemplate['id'], $nodeTemplate);

        return $nodeTemplate['id'];
    }

    /**
     * 获取规则模版
     * @param  array  $rules
     * @return array
     */
    private function formatRules(array $rules): array
    {
        // TODO: 做结构规范化/默认值补全/校验
        return $rules;
    }
}
