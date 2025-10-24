<?php

declare(strict_types=1);

namespace App\Flow4\State;

use App\Flow4\Engine\FlowContext;

/**
 * 审批流程状态接口
 * 设计模式：状态模式
 * 作用：定义审批流程在不同状态下的行为，实现状态转换的封装
 */
interface FlowStateInterface
{
    /**
     * 审批操作
     */
    public function approve(string $flowId, string $nodeId, string $approverId, array $data, FlowContext $context): void;

    /**
     * 驳回操作
     */
    public function reject(string $flowId, string $nodeId, string $approverId, array $data, FlowContext $context): void;

    /**
     * 取消操作
     */
    public function cancel(string $flowId, FlowContext $context): void;

    /**
     * 获取当前状态名称
     */
    public function getStateName(): string;

    /**
     * 获取允许的操作列表
     */
    public function getAllowedActions(): array;
}
