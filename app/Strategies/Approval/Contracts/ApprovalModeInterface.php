<?php

declare(strict_types=1);

namespace App\Strategies\Approval\Contracts;

use App\Models\FlowNode;

interface ApprovalModeInterface
{
    /**
     * 创建审批任务
     * @param  FlowNode  $node
     * @return void
     */
    public function createTasks(FlowNode $node): void;

    /**
     * 获取节点结果
     * @param  FlowNode  $node
     * @return mixed
     */
    public function getNodeResult(FlowNode $node);

    /**
     * 检查当前节点下任务是否满足通过条件
     * @param  FlowNode  $node
     * @return bool
     */
    public function isApproved(FlowNode $node): bool;

    /**
     * 模式是否支持
     * @param  string  $mode
     * @return bool
     */
    public static function supports(string $mode): bool;
}
