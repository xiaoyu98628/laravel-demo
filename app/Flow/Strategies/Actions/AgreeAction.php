<?php

declare(strict_types=1);

namespace App\Flow\Strategies\Actions;

use App\Constants\Enums\FlowNode\Method;
use App\Constants\Enums\FlowNodeTask\Status;
use App\Flow\Contracts\ActionStrategyInterface;
use App\Models\Flow;
use App\Models\FlowNode;
use App\Models\FlowNodeTask;

final class AgreeAction implements ActionStrategyInterface
{
    public function execute(Flow $flow, FlowNode $node, FlowNodeTask $task, array $payload = []): void
    {
        $task->status         = Status::APPROVE->value;
        $task->operation_info = ['action' => 'agree', 'comment' => $payload['comment'] ?? ''];
        $task->save();

        $rules = $node->rules ?? [];
        $mode  = Method::from($rules['mode'] ?? 'any');

        $total    = FlowNodeTask::query()->where('flow_node_id', $node->id)->count();
        $approved = FlowNodeTask::query()->where('flow_node_id', $node->id)->where('status', Status::APPROVE->value)->count();

        if ($mode === Method::Any) {
            // 任一通过即节点通过，未处理的任务置为 skip
            FlowNodeTask::query()->where('flow_node_id', $node->id)
                ->where('status', Status::PROCESS->value)
                ->update(['status' => Status::SKIP->value]);
            $node->status = Status::APPROVE->value;
            $node->save();
        } else {
            // 会签：全部通过才通过
            if ($approved >= $total) {
                $node->status = Status::APPROVE->value;
                $node->save();
            }
        }
    }
}
