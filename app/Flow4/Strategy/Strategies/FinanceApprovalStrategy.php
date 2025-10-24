<?php

declare(strict_types=1);

namespace App\Flow4\Strategy\Strategies;

use App\Flow4\Strategy\AbstractApprovalStrategy;
use App\Models\Flow;
use App\Models\FlowNode;

/**
 * 财务审批策略
 * 应用场景：财务相关审批的特殊处理逻辑
 * 设计模式：策略模式
 * 为什么选择：财务审批有特殊的金额限制、审批层级等业务规则
 */
class FinanceApprovalStrategy extends AbstractApprovalStrategy
{
    public function process(Flow $flow, FlowNode $node, array $data): void
    {
        // 财务审批特殊处理逻辑
        $amount = $flow->extend['amount'] ?? 0;

        // 根据金额设置不同的审批规则
        if ($amount > 100000) {
            // 大额审批需要额外验证
            $this->processHighAmountApproval($flow, $node, $data);
        } else {
            // 普通金额审批
            $this->processNormalApproval($flow, $node, $data);
        }
    }

    protected function validateApproverPermission(FlowNode $node, array $data): bool
    {
        // 财务审批权限验证
        if (!parent::validateApproverPermission($node, $data)) {
            return false;
        }

        // 额外的财务权限验证
        $approverId = $data['approver_id'];
        return $this->hasFinancePermission($approverId, $node);
    }

    private function processHighAmountApproval(Flow $flow, FlowNode $node, array $data): void
    {
        // 大额审批处理逻辑
        // 可能需要额外的风控检查
    }

    private function processNormalApproval(Flow $flow, FlowNode $node, array $data): void
    {
        // 普通审批处理逻辑
    }

    private function hasFinancePermission(string $approverId, FlowNode $node): bool
    {
        // 检查审批人是否有财务审批权限
        return true; // 这里应该实现具体的权限检查逻辑
    }
}
