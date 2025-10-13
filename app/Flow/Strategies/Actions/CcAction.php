<?php

declare(strict_types=1);

namespace App\Flow\Strategies\Actions;

use App\Constants\Enums\FlowNodeTask\Status;
use App\Flow\Contracts\ActionStrategyInterface;
use App\Models\Flow;
use App\Models\FlowNode;
use App\Models\FlowNodeTask;

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
