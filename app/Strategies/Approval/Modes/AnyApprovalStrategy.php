<?php

declare(strict_types=1);

namespace App\Strategies\Approval\Modes;

use App\Constants\Enums\FlowNode\Mode;
use App\Models\FlowNode;

class AnyApprovalStrategy extends AbstractApprovalModeStrategy
{
    public function getNodeResult(FlowNode $node)
    {
        // TODO: Implement getNodeResult() method.
    }

    /**
     * 是否支持
     * @param  string  $mode
     * @return bool
     */
    public function supports(string $mode): bool
    {
        return $mode == Mode::Any->value;
    }

    /**
     * 检查当前节点下任务是否满足通过条件
     * @param  FlowNode  $node
     * @return bool
     */
    public function isApproved(FlowNode $node): bool
    {
        return false;
    }
}
