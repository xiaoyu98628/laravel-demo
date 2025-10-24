<?php

declare(strict_types=1);

namespace App\Flow4\Engine;

use App\Flow4\State\FlowStateInterface;
use App\Flow4\State\States\CancelState;
use App\Flow4\State\States\CreateState;
use App\Flow4\State\States\ProcessState;
use App\Flow4\State\States\RejectState;
use App\Flow4\State\States\SuccessState;
use App\Flow4\State\States\WaitingState;
use App\Models\Flow;

/**
 * 流程上下文
 * 设计模式：状态模式的上下文类
 * 作用：维护流程的当前状态，协调各个组件的交互
 */
class FlowContext
{
    private Flow $flow;

    private FlowStateInterface $currentState;

    // 状态实例缓存
    private ?CreateState $createState = null;

    private ?ProcessState $processState = null;

    private ?SuccessState $successState = null;

    private ?RejectState $rejectState = null;

    private ?WaitingState $waitingState = null;

    private ?CancelState $cancelState = null;

    public function __construct(Flow $flow)
    {
        $this->flow = $flow;
        $this->initializeState();
    }

    /**
     * 根据流程状态初始化状态对象
     */
    private function initializeState(): void
    {
        $this->currentState = match ($this->flow->status) {
            'process' => $this->getProcessState(),
            'success' => $this->getSuccessState(),
            'reject'  => $this->getRejectState(),
            'waiting' => $this->getWaitingState(),
            'cancel'  => $this->getCancelState(),
            default   => $this->getCreateState()
        };
    }

    /**
     * 获取流程对象
     */
    public function getFlow(): Flow
    {
        return $this->flow;
    }

    /**
     * 设置当前状态
     */
    public function setState(FlowStateInterface $state): void
    {
        $this->currentState = $state;
    }

    /**
     * 获取当前状态
     */
    public function getCurrentState(): FlowStateInterface
    {
        return $this->currentState;
    }

    /**
     * 审批操作
     */
    public function approve(string $nodeId, string $approverId, array $data): void
    {
        $this->currentState->approve($this->flow->id, $nodeId, $approverId, $data, $this);

        // 重新加载流程数据
        $this->refreshFlow();
    }

    /**
     * 驳回操作
     */
    public function reject(string $nodeId, string $approverId, array $data): void
    {
        $this->currentState->reject($this->flow->id, $nodeId, $approverId, $data, $this);

        // 重新加载流程数据
        $this->refreshFlow();
    }

    /**
     * 取消操作
     */
    public function cancel(): void
    {
        $this->currentState->cancel($this->flow->id, $this);

        // 重新加载流程数据
        $this->refreshFlow();
    }

    /**
     * 获取允许的操作列表
     */
    public function getAllowedActions(): array
    {
        return $this->currentState->getAllowedActions();
    }

    /**
     * 获取当前状态名称
     */
    public function getStateName(): string
    {
        return $this->currentState->getStateName();
    }

    /**
     * 检查是否允许执行某个操作
     */
    public function canExecuteAction(string $action): bool
    {
        return in_array($action, $this->getAllowedActions());
    }

    /**
     * 重新加载流程数据
     */
    private function refreshFlow(): void
    {
        $this->flow = $this->flow->fresh();
    }

    // 状态获取方法（延迟加载）
    public function getCreateState(): CreateState
    {
        if ($this->createState === null) {
            $this->createState = app(CreateState::class);
        }

        return $this->createState;
    }

    public function getProcessState(): ProcessState
    {
        if ($this->processState === null) {
            $this->processState = app(ProcessState::class);
        }

        return $this->processState;
    }

    public function getSuccessState(): SuccessState
    {
        if ($this->successState === null) {
            $this->successState = app(SuccessState::class);
        }

        return $this->successState;
    }

    public function getRejectState(): RejectState
    {
        if ($this->rejectState === null) {
            $this->rejectState = app(RejectState::class);
        }

        return $this->rejectState;
    }

    public function getWaitingState(): WaitingState
    {
        if ($this->waitingState === null) {
            $this->waitingState = app(WaitingState::class);
        }

        return $this->waitingState;
    }

    public function getCancelState(): CancelState
    {
        if ($this->cancelState === null) {
            $this->cancelState = app(CancelState::class);
        }

        return $this->cancelState;
    }
}
