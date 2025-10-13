<?php
declare(strict_types=1);

namespace App\Flow\Handlers;

use App\Constants\Enums\FlowNode\Type;
use App\Flow\Contracts\NodeHandlerInterface;
use App\Models\Flow;
use App\Models\FlowNode;

final class ConditionRouteNodeHandler implements NodeHandlerInterface
{
    public function supports(string $nodeType): bool
    {
        return $nodeType === Type::CONDITION_ROUTE->value;
    }

    public function handle(Flow $flow, FlowNode $node): void
    {
        // 具体路由节点属于条件分支的具体分支，通常直接进入后续节点；此处标记跳过
        $node->status = 'skip';
        $node->save();
    }

}
