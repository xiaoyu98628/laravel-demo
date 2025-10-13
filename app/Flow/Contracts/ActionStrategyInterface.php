<?php
   declare(strict_types=1);

namespace App\Flow\Contracts;

use App\Models\Flow;
use App\Models\FlowNode;
use App\Models\FlowNodeTask;

interface ActionStrategyInterface
{
    // 在某个任务上执行动作
    public function execute(Flow $flow, FlowNode $node, FlowNodeTask $task, array $payload = []): void;
}