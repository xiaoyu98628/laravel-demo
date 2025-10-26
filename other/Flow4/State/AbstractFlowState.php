<?php

declare(strict_types=1);

namespace other\Flow4\State;

use App\Repositories\FlowRepositories;
use other\Flow4\Engine\FlowContext;

/**
 * 抽象审批流程状态
 * 实现状态的通用逻辑
 */
abstract class AbstractFlowState implements FlowStateInterface
{
    protected FlowRepositories $flowRepository;

    public function __construct()
    {
        $this->flowRepository = app(FlowRepositories::class);
    }

    /**
     * 默认的取消操作 - 大部分状态都支持取消
     */
    public function cancel(string $flowId, FlowContext $context): void
    {
        $this->flowRepository->update($flowId, ['status' => 'cancel']);
        $context->setState($context->getCancelState());
    }

    /**
     * 状态转换的通用逻辑
     */
    protected function changeState(FlowContext $context, FlowStateInterface $newState, string $flowId, string $status): void
    {
        $this->flowRepository->update($flowId, ['status' => $status]);
        $context->setState($newState);
    }

    /**
     * 检查操作权限
     */
    protected function checkPermission(string $action): void
    {
        if (! in_array($action, $this->getAllowedActions())) {
            throw new \InvalidArgumentException("当前状态[{$this->getStateName()}]不允许执行操作: {$action}");
        }
    }
}
