<?php

declare(strict_types=1);

namespace other\Flow4\ChainOfResponsibility\Handlers;

use App\Models\FlowNode;
use other\Flow4\ChainOfResponsibility\AbstractNodeHandler;
use other\Flow4\Engine\FlowContext;

/**
 * 审批节点处理器
 * 设计模式：责任链模式
 * 应用场景：处理普通的审批节点，执行审批逻辑
 */
class ApprovalNodeHandler extends AbstractNodeHandler
{
    public function getSupportedNodeType(): string
    {
        return 'approval';
    }

    protected function doHandle(FlowNode $node, array $data, FlowContext $context): void
    {
        // 审批节点处理逻辑
        $flow = $context->getFlow();

        // 使用策略模式获取对应的审批策略
        $strategy = $this->getApprovalStrategy($flow->business_type);

        // 验证审批条件
        if (! $strategy->validate($flow, $node, $data)) {
            throw new \InvalidArgumentException('审批条件验证失败');
        }

        // 执行审批逻辑
        $strategy->process($flow, $node, $data);

        // 检查节点是否完成
        if ($this->isNodeCompleted($node, $strategy)) {
            $this->completeNode($node, $context);
        }
    }

    private function getApprovalStrategy(string $businessType): \other\Flow4\Strategy\ApprovalStrategyInterface
    {
        $strategyClass = 'App\\Flow\\Strategy\\Strategies\\'.ucfirst($businessType).'ApprovalStrategy';

        if (! class_exists($strategyClass)) {
            $strategyClass = 'App\\Flow\\Strategy\\Strategies\\GeneralApprovalStrategy';
        }

        return app($strategyClass);
    }

    private function isNodeCompleted(FlowNode $node, \other\Flow4\Strategy\ApprovalStrategyInterface $strategy): bool
    {
        // 根据审批方式判断节点是否完成
        $approvalMethod = $node->rules['approval_method'] ?? 'any';
        $approvers      = $node->rules['approvers']       ?? [];

        return match ($approvalMethod) {
            'all'   => $strategy->handleCountersign($node, $approvers),
            'any'   => $strategy->handleParallelSign($node, $approvers),
            default => true
        };
    }

    private function completeNode(FlowNode $node, FlowContext $context): void
    {
        // 更新节点状态
        app(\App\Repositories\FlowNodeRepositories::class)->update($node->id, [
            'status' => 'approve',
        ]);

        // 流转到下一个节点
        $nextNode = $this->getNextNode($node, $context);

        if ($nextNode) {
            // 继续处理下一个节点
            $this->handle($nextNode, [], $context);
        } else {
            // 没有下一个节点，流程结束
            $this->completeFlow($context);
        }
    }

    private function getNextNode(FlowNode $node, FlowContext $context): ?FlowNode
    {
        // 从流程快照中获取下一个节点
        // 这里需要实现具体的节点查找逻辑
        return null;
    }

    private function completeFlow(FlowContext $context): void
    {
        $flow = $context->getFlow();
        app(\App\Repositories\FlowRepositories::class)->update($flow->id, [
            'status' => 'success',
        ]);

        $context->setState($context->getSuccessState());
    }
}
