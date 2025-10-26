<?php

declare(strict_types=1);

namespace other\Flow4\State\States;

use other\Flow4\Engine\FlowContext;
use other\Flow4\State\AbstractFlowState;

/**
 * 成功状态 - 流程审批通过的状态
 */
class SuccessState extends AbstractFlowState
{
    public function approve(string $flowId, string $nodeId, string $approverId, array $data, FlowContext $context): void
    {
        throw new \InvalidArgumentException('流程已通过，不能再进行审批操作');
    }

    public function reject(string $flowId, string $nodeId, string $approverId, array $data, FlowContext $context): void
    {
        throw new \InvalidArgumentException('流程已通过，不能进行驳回操作');
    }

    public function cancel(string $flowId, FlowContext $context): void
    {
        throw new \InvalidArgumentException('流程已通过，不能取消');
    }

    public function getStateName(): string
    {
        return 'success';
    }

    public function getAllowedActions(): array
    {
        return ['view']; // 只允许查看
    }
}
