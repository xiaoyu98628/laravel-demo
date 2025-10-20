<?php

declare(strict_types=1);

namespace App\Flow2\Handlers;

use App\Constants\Enums\FlowNode\Type;
use App\Constants\Enums\FlowNodeTask\Status as FlowNodeTaskStatus;
use App\Flow2\Contracts\NodeHandlerInterface;
use App\Models\Flow;
use App\Models\FlowNode;
use App\Models\FlowNodeTask;
use Illuminate\Support\Arr;

final class ApprovalNodeHandler implements NodeHandlerInterface
{
    public function supports(string $nodeType): bool
    {
        return $nodeType === Type::APPROVAL->value;
    }

    public function handle(Flow $flow, FlowNode $node): void
    {
        // 生成任务（为兼容仓库层字段问题，使用模型直接创建）
        $rules     = $node->rules ?? [];
        $approvers = Arr::get($rules, 'approvers', []);
        foreach ($approvers as $ap) {
            FlowNodeTask::query()->create([
                'approver_id'    => $ap['id'],
                'approver_name'  => $ap['name'],
                'approver_type'  => $ap['type'] ?? 'user',
                'operation_info' => null,
                'status'         => FlowNodeTaskStatus::PROCESS->value,
                'flow_node_id'   => $node->id,
                'extend'         => null,
            ]);
        }
        // 节点进入审批中
        $node->status = FlowNodeTaskStatus::PROCESS->value;
        $node->save();
    }
}
