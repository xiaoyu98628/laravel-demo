<?php

declare(strict_types=1);

namespace other\Flow4\ChainOfResponsibility\Handlers;

use App\Models\FlowNode;
use other\Flow4\ChainOfResponsibility\AbstractNodeHandler;
use other\Flow4\Engine\FlowContext;

/**
 * 条件节点处理器
 * 设计模式：责任链模式
 * 应用场景：处理条件分支节点，根据条件决定流程走向
 */
class ConditionNodeHandler extends AbstractNodeHandler
{
    public function getSupportedNodeType(): string
    {
        return 'condition';
    }

    protected function doHandle(FlowNode $node, array $data, FlowContext $context): void
    {
        // 条件节点处理逻辑
        $flow = $context->getFlow();
        $conditions = $node->rules['conditions'] ?? [];

        // 评估所有条件
        foreach ($conditions as $condition) {
            if ($this->evaluateCondition($condition, $flow, $data)) {
                // 条件满足，创建对应的路由节点
                $this->createRouteNode($condition, $node, $context);
                return;
            }
        }

        // 没有条件满足，使用默认路由
        $this->createDefaultRoute($node, $context);
    }

    private function evaluateCondition(array $condition, \App\Models\Flow $flow, array $data): bool
    {
        $field = $condition['field'] ?? '';
        $operator = $condition['operator'] ?? '=';
        $value = $condition['value'] ?? '';

        // 从流程数据中获取字段值
        $fieldValue = $this->getFieldValue($field, $flow, $data);

        // 根据操作符评估条件
        return $this->compareValues($fieldValue, $operator, $value);
    }

    private function getFieldValue(string $field, \App\Models\Flow $flow, array $data)
    {
        // 从不同来源获取字段值
        if (str_starts_with($field, 'flow.')) {
            $fieldName = substr($field, 5);
            return $flow->getAttribute($fieldName) ?? $flow->extend[$fieldName] ?? null;
        }

        if (str_starts_with($field, 'data.')) {
            $fieldName = substr($field, 5);
            return $data[$fieldName] ?? null;
        }

        return $data[$field] ?? null;
    }

    private function compareValues($fieldValue, string $operator, $expectedValue): bool
    {
        return match ($operator) {
            '=' => $fieldValue == $expectedValue,
            '!=' => $fieldValue != $expectedValue,
            '>' => $fieldValue > $expectedValue,
            '>=' => $fieldValue >= $expectedValue,
            '<' => $fieldValue < $expectedValue,
            '<=' => $fieldValue <= $expectedValue,
            'in' => in_array($fieldValue, (array)$expectedValue),
            'not_in' => !in_array($fieldValue, (array)$expectedValue),
            'contains' => str_contains((string)$fieldValue, (string)$expectedValue),
            default => false
        };
    }

    private function createRouteNode(array $condition, FlowNode $conditionNode, FlowContext $context): void
    {
        // 根据条件创建对应的路由节点
        $targetNodeTemplate = $condition['target_node'] ?? null;

        if (!$targetNodeTemplate) {
            throw new \RuntimeException('条件节点配置错误：缺少目标节点');
        }

        $this->createNodesFromTemplate($targetNodeTemplate, $conditionNode, $context);
    }

    private function createDefaultRoute(FlowNode $conditionNode, FlowContext $context): void
    {
        // 创建默认路由节点
        $defaultRoute = $conditionNode->rules['default_route'] ?? null;

        if ($defaultRoute) {
            $this->createNodesFromTemplate($defaultRoute, $conditionNode, $context);
        } else {
            // 没有默认路由，直接结束流程
            $this->completeFlow($context);
        }
    }

    private function createNodesFromTemplate(array $nodeTemplate, FlowNode $parentNode, FlowContext $context): void
    {
        // 根据节点模板创建实际的流程节点
        $nodeRepository = app(\App\Repositories\FlowNodeRepositories::class);

        $nodeData = [
            'flow_id' => $parentNode->flow_id,
            'name' => $nodeTemplate['name'] ?? '审批节点',
            'type' => $nodeTemplate['type'] ?? 'approval',
            'depth' => $parentNode->depth + 1,
            'parent_id' => $parentNode->id,
            'status' => 'process',
            'rules' => $nodeTemplate['rules'] ?? [],
            'extend' => $nodeTemplate['extend'] ?? []
        ];

        $newNode = $nodeRepository->store($nodeData);

        // 继续处理新创建的节点
        $this->handle($newNode, [], $context);
    }

    private function completeFlow(FlowContext $context): void
    {
        $flow = $context->getFlow();
        app(\App\Repositories\FlowRepositories::class)->update($flow->id, [
            'status' => 'success'
        ]);

        $context->setState($context->getSuccessState());
    }
}
