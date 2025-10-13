<?php

declare(strict_types=1);

namespace App\Flow\Strategies\Actions;

use App\Constants\Enums\FlowNodeTask\Status;
use App\Flow\Contracts\ActionStrategyInterface;
use App\Models\Flow;
use App\Models\FlowNode;
use App\Models\FlowNodeTask;

final class RejectAction implements ActionStrategyInterface
{
    public function execute(Flow $flow, FlowNode $node, FlowNodeTask $task, array $payload = []): void
    {
        $task->status         = Status::REJECT->value;
        $task->operation_info = ['action' => 'reject', 'comment' => $payload['comment'] ?? ''];
        $task->save();

        // 一票否决：节点直接驳回
        $node->status = Status::REJECT->value;
        $node->save();
    }
}
