<?php

declare(strict_types=1);

namespace other\Flow4\Strategy;

use App\Models\Flow;
use App\Models\FlowNode;

/**
 * 审批策略接口
 * 设计模式：策略模式
 * 作用：定义不同审批类型的处理策略，实现算法族的封装和互换
 */
interface ApprovalStrategyInterface
{
    /**
     * 处理审批逻辑
     */
    public function process(Flow $flow, FlowNode $node, array $data): void;

    /**
     * 验证审批条件
     */
    public function validate(Flow $flow, FlowNode $node, array $data): bool;

    /**
     * 获取下一个节点
     */
    public function getNextNode(Flow $flow, FlowNode $currentNode): ?FlowNode;

    /**
     * 处理会签逻辑
     */
    public function handleCountersign(FlowNode $node, array $approvers): bool;

    /**
     * 处理并签逻辑
     */
    public function handleParallelSign(FlowNode $node, array $approvers): bool;
}
