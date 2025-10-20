<?php

declare(strict_types=1);

namespace App\Flow2\Engine;

use App\Constants\Enums\FlowNode\Type;

final class FlowNavigator
{
    public function __construct(private array $snapshot) {}

    public function root(): array
    {
        return $this->snapshot;
    }

    public function find(string $tplNodeId): ?array
    {
        return $this->dfsFind($this->snapshot, $tplNodeId);
    }

    public function next(array $currentTplNode, array $business = []): ?array
    {
        // 简化：优先 children（线性主干），若当前是条件节点则根据规则选择 conditionNode 中的一个分支
        // 实际可实现 evaluateCondition(...) 按业务快照判定
        $type = $currentTplNode['type'];

        // 1) 条件节点：从 conditionNode 中选择一条路由（Demo：取第一条）
        if ($type === Type::CONDITION->value && ! empty($currentTplNode['condition_node'])) {
            return $currentTplNode['condition_node'][0] ?? null;
        }

        // 2) 普通节点：走 children 指向的下一个
        if (! empty($currentTplNode['children'])) {
            return $currentTplNode['children'];
        }

        // 3) 无子节点，说明到头了
        return null;
    }

    private function dfsFind(array $node, string $tplNodeId): ?array
    {
        if (($node['id'] ?? '') === $tplNodeId) {
            return $node;
        }

        // condition 分支
        foreach ($node['condition_node'] ?? [] as $cond) {
            if ($found = $this->dfsFind($cond, $tplNodeId)) {
                return $found;
            }
        }
        // 主干 children（注意你的 FlowNodeTemplate::children 是 hasOne）
        if (! empty($node['children'])) {
            if ($found = $this->dfsFind($node['children'], $tplNodeId)) {
                return $found;
            }
        }

        return null;
    }
}
