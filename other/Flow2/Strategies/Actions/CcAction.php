<?php

declare(strict_types=1);

namespace other\Flow2\Strategies\Actions;

use App\Constants\Enums\FlowNodeTask\Status;
use App\Models\Flow;
use App\Models\FlowNode;
use App\Models\FlowNodeTask;
use other\Flow2\Contracts\ActionStrategyInterface;

final class CcAction implements ActionStrategyInterface
{
    public function execute(Flow $flow, FlowNode $node, FlowNodeTask $task, array $payload = []): void
    {
        $task->status         = Status::AUTO->value;
        $task->operation_info = ['action' => 'cc'];
        $task->save();
        $node->status = Status::AUTO->value;
        $node->save();
    }
}
