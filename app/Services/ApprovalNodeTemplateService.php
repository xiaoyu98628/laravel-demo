<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ApprovalNodeTemplate;
use App\Repositories\ApprovalNodeTemplateRepositories;
use Illuminate\Support\Arr;
use Service\Common\Base\BaseRepository;

class ApprovalNodeTemplateService extends BaseRepository
{
    public function __construct(
        private readonly ApprovalNodeTemplateRepositories $repositories,
    ) {}

    /**
     * 处理树形数据
     * @param  string  $templateId
     * @param  array  $nodes
     * @param  string|null  $parentId
     * @param  int  $stepOrder
     * @return array
     */
    public function handleNodeTemplateTree(string $templateId, array $nodes, ?string $parentId = null, int $stepOrder = 1): array
    {
        $idArr = [];
        foreach ($nodes as $node) {
            $id      = $this->upsert($templateId, $node, $stepOrder, $parentId);
            $idArr[] = $id;

            if (! empty($node['children'])) {
                $idArr = array_merge($idArr, $this->handleNodeTemplateTree($templateId, $node['children'], $id, $stepOrder + 1));
            }
        }

        return $idArr;
    }

    /**
     * 创建或更新
     * @param  string  $templateId
     * @param  array  $node
     * @param  int  $stepOrder
     * @param  string|null  $parentId
     * @return string
     */
    private function upsert(string $templateId, array $node, int $stepOrder, ?string $parentId = null): string
    {
        $node['template_id'] = $templateId;
        $node['depth']       = $stepOrder;
        $node['parent_id']   = $parentId;
        $node['rules']       = $this->formatRules(Arr::get($node, 'rules', []));

        if (empty($node['id'])) {
            /** @var ApprovalNodeTemplate $model */
            $model = $this->repositories->store($node);
            return $model->id;
        }

        $this->repositories->update($node['id'], $node);

        return $node['id'];
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
