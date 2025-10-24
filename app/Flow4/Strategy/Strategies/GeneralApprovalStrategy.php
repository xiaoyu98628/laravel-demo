<?php

declare(strict_types=1);

namespace App\Flow4\Strategy\Strategies;

use App\Flow4\Strategy\AbstractApprovalStrategy;
use App\Models\Flow;
use App\Models\FlowNode;

/**
 * 通用审批策略
 * 应用场景：默认的审批处理逻辑，适用于没有特殊业务规则的审批类型
 * 设计模式：策略模式
 * 为什么选择：提供标准的审批处理流程，作为其他策略的基础实现
 */
class GeneralApprovalStrategy extends AbstractApprovalStrategy
{
    public function process(Flow $flow, FlowNode $node, array $data): void
    {
        // 通用审批处理逻辑
        $approvalMethod = $node->rules['approval_method'] ?? 'any';

        switch ($approvalMethod) {
            case 'all': // 会签
                $this->processCountersignApproval($flow, $node, $data);
                break;
            case 'any': // 并签
                $this->processParallelSignApproval($flow, $node, $data);
                break;
            default:
                $this->processSingleApproval($flow, $node, $data);
        }
    }

    private function processCountersignApproval(Flow $flow, FlowNode $node, array $data): void
    {
        // 会签处理逻辑
        $approvers  = $this->getNodeApprovers($node);
        $isComplete = $this->handleCountersign($node, $approvers);

        if ($isComplete) {
            $this->completeNode($node);
        }
    }

    private function processParallelSignApproval(Flow $flow, FlowNode $node, array $data): void
    {
        // 并签处理逻辑
        $approvers  = $this->getNodeApprovers($node);
        $isComplete = $this->handleParallelSign($node, $approvers);

        if ($isComplete) {
            $this->completeNode($node);
        }
    }

    private function processSingleApproval(Flow $flow, FlowNode $node, array $data): void
    {
        // 单人审批逻辑
        $this->completeNode($node);
    }

    private function getNodeApprovers(FlowNode $node): array
    {
        return $node->rules['approvers'] ?? [];
    }

    private function completeNode(FlowNode $node): void
    {
        // 完成节点处理
        $this->nodeRepository->update($node->id, [
            'status' => 'approve',
        ]);
    }
}
