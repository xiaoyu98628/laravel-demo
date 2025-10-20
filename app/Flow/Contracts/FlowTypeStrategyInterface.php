<?php

declare(strict_types=1);

namespace App\Flow\Contracts;

use App\Models\FlowNodeTemplate;
use App\Models\FlowTemplate;

/**
 * 审批流程接口
 */
interface FlowTypeStrategyInterface
{
    // 选择模板 + 预校验 + 业务快照组装
    public function flowTemplate(array $inputs): FlowTemplate;

    public function flowNodeTemplate(array $inputs): FlowNodeTemplate;

    // 标题构建
    public function makeTitle(array $inputs): string;

    // 数据组装
    public function buildFlow(array $inputs): array;

    // 业务快照
    public function buildBusinessSnapshot(array $inputs): array;
}
