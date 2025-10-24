<?php

declare(strict_types=1);

namespace App\Flow4\State\States;

use App\Flow4\Engine\FlowContext;
use App\Flow4\State\AbstractFlowState;

/**
 * 驳回状态 - 流程被驳回的状态
 */
class RejectState extends AbstractFlowState
{
    public function approve(string $flowId, string $nodeId, string $approverId, array $data, FlowContext $context): void
    {
        throw new \InvalidArgumentException('流程已驳回，不能进行审批操作');
    }

    public function reject(string $flowId, string $nodeId, string $approverId, array $data, FlowContext $context): void
    {
        throw new \InvalidArgumentException('流程已驳回，不能再次驳回');
    }

    public function getStateName(): string
    {
        return 'reject';
    }
    public function getAllowedActions(): array
    {
        return ['view', 'resubmit']; // 允许查看和重新提交
    }

    /**
     * 重新提交流程 - 从驳回状态转换到处理状态
     */
    public function resubmit(string $flowId, FlowContext $context): void
    {
        $this->changeState($context, $context->getProcessState(), $flowId, 'process');
    }
}
