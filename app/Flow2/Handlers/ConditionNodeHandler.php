<?php
declare(strict_types=1);

namespace App\Flow2\Handlers;

use App\Constants\Enums\FlowNode\Type;
use App\Flow2\Contracts\NodeHandlerInterface;
use App\Models\Flow;
use App\Models\FlowNode;

final class ConditionNodeHandler implements NodeHandlerInterface
{
    public function supports(string $nodeType): bool
    {
        return $nodeType === Type::CONDITION->value;
    }

    public function handle(Flow $flow, FlowNode $node): void
    {
        // 条件节点不做审批，仅计算路由。真实路由选择由 ConditionRouteNodeHandler 或引擎调度完成
        // 这里可以将计算结果写入 node->extend['route_passed'] 供后续路由使用
        $rule = $node->rules ?? [];
        // 简化：若业务预算>=阈值，走 A 路由，否则 B
        // 此处仅demo，不落库
        $node->status = 'skip';
        $node->save();
    }

}
