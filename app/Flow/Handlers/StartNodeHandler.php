<?php
declare(strict_types=1);

namespace App\Flow\Handlers;

use App\Constants\Enums\FlowNode\Type;
use App\Constants\Enums\FlowNodeTask\Status as FlowNodeTaskStatus;
use App\Flow\Contracts\NodeHandlerInterface;
use App\Models\Flow;
use App\Models\FlowNode;

final class StartNodeHandler implements NodeHandlerInterface
{
    public function supports(string $nodeType): bool
    {
        return $nodeType === Type::START->value;
    }

    public function handle(Flow $flow, FlowNode $node): void
    {
        // 开始节点自动通过
        $node->status = FlowNodeTaskStatus::AUTO->value;
        $node->save();

        // 引擎将选择下一个（责任链在引擎聚合）
    }

}
