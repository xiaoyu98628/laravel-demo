<?php

declare(strict_types=1);

namespace App\Flow\Contracts;

use Illuminate\Database\Eloquent\Model;

/**
 * 审批流程接口
 */
interface FlowTypeStrategyInterface
{
    // 选择模板 + 预校验 + 业务快照组装
    public function selectTemplate(array $inputs): Model;

    // 数据组装
    public function buildFlow(array $inputs): array;

    // 业务快照
    public function buildBusinessSnapshot(array $inputs): array;

    // 标题构建
    public function makeTitle(array $inputs): string;

    // 可进行模板内节点规则定制（可选）
    public function enrichNodeRules(array $nodeTemplateTree, array $inputs): array;
}
