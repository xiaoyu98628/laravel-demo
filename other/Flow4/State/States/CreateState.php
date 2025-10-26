<?php

declare(strict_types=1);

namespace other\Flow4\State\States;

use other\Flow4\Engine\FlowContext;
use other\Flow4\State\AbstractFlowState;

/**
 * 创建状态 - 流程刚创建时的状态
 * 设计模式：状态模式
 * 应用场景：处理草稿状态的流程，支持编辑、提交、取消操作
 */
class CreateState extends AbstractFlowState
{
    public function approve(string $flowId, string $nodeId, string $approverId, array $data, FlowContext $context): void
    {
        throw new \InvalidArgumentException('草稿状态的流程不能进行审批操作，请先提交流程');
    }

    public function reject(string $flowId, string $nodeId, string $approverId, array $data, FlowContext $context): void
    {
        throw new \InvalidArgumentException('草稿状态的流程不能进行驳回操作');
    }

    public function getStateName(): string
    {
        return 'create';
    }

    public function getAllowedActions(): array
    {
        return ['submit', 'edit', 'cancel'];
    }

    /**
     * 提交流程 - 从创建状态转换到处理状态
     */
    public function submit(string $flowId, FlowContext $context): void
    {
        $this->changeState($context, $context->getProcessState(), $flowId, 'process');
    }
}
