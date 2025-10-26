<?php
declare(strict_types=1);

namespace other\Flow2\Contracts;

use App\Models\FlowTemplate;

interface ApprovalTypeStrategyInterface
{
    // 选择模板 + 预校验 + 业务快照组装
    public function selectTemplate(array $inputs): FlowTemplate;

    // 业务快照
    public function buildBusinessSnapshot(array $inputs): array;

    // 标题构建
    public function makeTitle(array $inputs): string;

    // 可进行模板内节点规则定制（可选）
    public function enrichNodeRules(array $nodeTemplateTree, array $inputs): array;

}
