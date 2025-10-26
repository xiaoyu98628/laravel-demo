<?php

declare(strict_types=1);

namespace other\Flow4\State\States;

use other\Flow4\Engine\FlowContext;
use other\Flow4\State\AbstractFlowState;

/**
 * 等待状态 - 流程暂停等待的状态
 * 应用场景：当流程需要等待外部条件满足时使用
 */
class WaitingState extends AbstractFlowState
{
    public function approve(string $flowId, string $nodeId, string $approverId, array $data, FlowContext $context): void
    {
        throw new \InvalidArgumentException('流程处于等待状态，暂时不能进行审批操作');
    }

    public function reject(string $flowId, string $nodeId, string $approverId, array $data, FlowContext $context): void
    {
        $this->checkPermission('reject');
        $this->changeState($context, $context->getRejectState(), $flowId, 'reject');
    }

    public function getStateName(): string
    {
        return 'waiting';
    }

    public function getAllowedActions(): array
    {
        return ['reject', 'cancel', 'resume'];
    }

    /**
     * 恢复流程 - 从等待状态转换到处理状态
     */
    public function resume(string $flowId, FlowContext $context): void
    {
        $this->changeState($context, $context->getProcessState(), $flowId, 'process');
    }
}
