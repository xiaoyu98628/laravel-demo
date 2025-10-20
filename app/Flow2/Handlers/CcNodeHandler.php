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

final class CcNodeHandler implements NodeHandlerInterface
{
    public function supports(string $nodeType): bool
    {
        return $nodeType === Type::CC->value;
    }

    public function handle(Flow $flow, FlowNode $node): void
    {
        $rules = $node->rules ?? [];
        $ccers = Arr::get($rules, 'cc_list', []);
        foreach ($ccers as $cc) {
            FlowNodeTask::query()->create([
                'approver_id'    => $cc['id'],
                'approver_name'  => $cc['name'],
                'approver_type'  => $cc['type'] ?? 'user',
                'operation_info' => ['action' => 'cc'],
                'status'         => FlowNodeTaskStatus::AUTO->value,
                'flow_node_id'   => $node->id,
                'extend'         => null,
            ]);
        }
        $node->status = FlowNodeTaskStatus::AUTO->value;
        $node->save();
    }
}
