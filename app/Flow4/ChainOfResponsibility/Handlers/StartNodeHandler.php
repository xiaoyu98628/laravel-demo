<?php

declare(strict_types=1);

namespace App\Flow4\ChainOfResponsibility\Handlers;

use App\Flow4\ChainOfResponsibility\AbstractNodeHandler;
use App\Flow4\Engine\FlowContext;
use App\Models\FlowNode;

/**
 * 开始节点处理器
 * 设计模式：责任链模式
 * 应用场景：处理流程的开始节点，初始化流程数据
 */
class StartNodeHandler extends AbstractNodeHandler
{
    public function getSupportedNodeType(): string
    {
        return 'start';
    }

    protected function doHandle(FlowNode $node, array $data, FlowContext $context): void
    {
        // 开始节点处理逻辑
        $flow = $context->getFlow();

        // 更新流程状态为处理中
        if ($flow->status === 'create') {
            app(\App\Repositories\FlowRepositories::class)->update($flow->id, [
                'status' => 'process',
            ]);

            // 转换状态
            $context->setState($context->getProcessState());
        }

        // 创建开始节点记录
        $nodeRepository = app(\App\Repositories\FlowNodeRepositories::class);
        $startNodeData  = [
            'flow_id'   => $flow->id,
            'name'      => $node->name ?? '开始',
            'type'      => 'start',
            'depth'     => 1,
            'parent_id' => null,
            'status'    => 'approve', // 开始节点默认通过
            'rules'     => [],
            'extend'    => $data['extend'] ?? [],
        ];

        $startNode = $nodeRepository->store($startNodeData);

        // 自动流转到下一个节点
        $this->moveToNextNode($startNode, $context);
    }

    private function moveToNextNode(FlowNode $startNode, FlowContext $context): void
    {
        // 从流程快照中找到下一个节点并创建
        $flow     = $context->getFlow();
        $snapshot = $flow->flow_node_template_snapshot ?? [];

        $this->createNextNodes($snapshot, $startNode, $context);
    }

    private function createNextNodes(array $snapshot, FlowNode $parentNode, FlowContext $context): void
    {
        // 根据模板快照创建下一批节点
        // 这里需要实现具体的节点创建逻辑
    }
}
