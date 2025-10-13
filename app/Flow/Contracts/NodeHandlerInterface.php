<?php
   declare(strict_types=1);

namespace App\Flow\Contracts;

use App\Models\Flow;
use App\Models\FlowNode;

interface NodeHandlerInterface
{
    // 是否能处理该类型
    public function supports(string $nodeType): bool;

    // 处理当前节点（返回：是否完成处理并推动到下一个）
    public function handle(Flow $flow, FlowNode $node): void;

}