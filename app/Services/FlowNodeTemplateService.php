<?php

declare(strict_types=1);

namespace App\Services;

use App\Constants\Enums\FlowNodeTemplate\Type;
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
     * @param  int  $stepOrder
     * @param  string|null  $parentId
     * @param  array|null  $idArr
     * @return array
     * @throws \Exception
     */
    public function handleNodeTemplateTree(string $flowTemplateId, array $nodeTemplates, int $stepOrder = 1, ?string $parentId = null, ?array $idArr = []): array
    {
        if (! in_array(Arr::get($nodeTemplates, 'type'), Type::values())) {
            throw new \Exception('错误的节点类型');
        }

        $nodeId  = $this->upsert($flowTemplateId, $nodeTemplates, $stepOrder, $parentId);
        $idArr[] = $nodeId;

        match (Arr::get($nodeTemplates, 'type')) {
            Type::CONDITION_ROUTE->value => value(function () use ($flowTemplateId, $nodeTemplates, $stepOrder, $nodeId, &$idArr) {
                foreach (Arr::get($nodeTemplates, 'condition_node', []) as $nodeTemplate) {
                    $id      = $this->upsert($flowTemplateId, $nodeTemplate, $stepOrder, $nodeId);
                    $idArr[] = $id;

                    if (! empty(Arr::get($nodeTemplate, 'children', []))) {
                        $idArr = [...$idArr, ...$this->handleNodeTemplateTree($flowTemplateId, Arr::get($nodeTemplate, 'children', []), $stepOrder + 1, $id)];
                    }
                }
            }),
            default => '',
        };

        if (! empty(Arr::get($nodeTemplates, 'children', []))) {
            $idArr = [...$idArr, ...$this->handleNodeTemplateTree($flowTemplateId, Arr::get($nodeTemplates, 'children', []), $stepOrder + 1, $nodeId)];
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
     * @throws \Exception
     */
    private function upsert(string $flowTemplateId, array $nodeTemplate, int $stepOrder, ?string $parentId = null): string
    {
        $nodeTemplate['flow_template_id'] = $flowTemplateId;
        $nodeTemplate['depth']            = $stepOrder;
        $nodeTemplate['parent_id']        = $parentId;
        $nodeTemplate['rules']            = $this->formatRules(Arr::get($nodeTemplate, 'rules', []));

        if (empty($nodeTemplate['id'])) {
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
