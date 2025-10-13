<?php

declare(strict_types=1);

namespace App\Flow\Strategies\Actions;

use App\Flow\Contracts\ActionStrategyInterface;
use App\Models\Flow;
use App\Models\FlowNode;
use App\Models\FlowNodeTask;

final class AgreeAction implements ActionStrategyInterface
{
    public function __construct(private readonly \App\Flow\Engine\FlowEngine $engine) {}

    public function execute(Flow $flow, FlowNode $node, FlowNodeTask $task, array $payload = []): void
    {
        // ...原有同意逻辑（更新任务/节点状态）
        // 完成后告知引擎
        $this->engine->afterNodeSettled($flow, $node);
    }
}
