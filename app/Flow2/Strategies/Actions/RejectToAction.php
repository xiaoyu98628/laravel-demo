<?php

declare(strict_types=1);

namespace App\Flow2\Strategies\Actions;

use App\Flow2\Contracts\ActionStrategyInterface;
use App\Flow2\Engine\FlowEngine;
use App\Models\Flow;
use App\Models\FlowNode;
use App\Models\FlowNodeTask;

final class RejectToAction implements ActionStrategyInterface
{
    public function __construct(private readonly FlowEngine $engine) {}

    public function execute(Flow $flow, FlowNode $node, FlowNodeTask $task, array $payload = []): void
    {
        // 记录当前任务为 reject
        $task->status         = 'reject';
        $task->operation_info = ['action' => 'reject_to', 'comment' => $payload['comment'] ?? '', 'target_tpl_node_id' => $payload['target_tpl_node_id'] ?? ''];
        $task->save();

        // 当前节点置为 cancel 或 reject（按业务约定，这里用 cancel）
        $node->status = 'cancel';
        $node->save();

        // 调用引擎回退到目标模板节点
        $targetTplNodeId = (string) ($payload['target_tpl_node_id'] ?? '');
        if (empty($targetTplNodeId)) {
            throw new \InvalidArgumentException('缺少目标模板节点ID');
        }
        $this->engine->rejectToTemplateNode($flow, $targetTplNodeId, $payload['comment'] ?? '');
    }
}
