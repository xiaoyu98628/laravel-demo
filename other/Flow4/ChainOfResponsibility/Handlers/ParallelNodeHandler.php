<?php

declare(strict_types=1);

namespace other\Flow4\ChainOfResponsibility\Handlers;

use App\Models\FlowNode;
use other\Flow4\ChainOfResponsibility\AbstractNodeHandler;
use other\Flow4\Engine\FlowContext;

/**
 * 并行节点处理器
 * 设计模式：责任链模式
 * 应用场景：处理并行分支节点，创建多个并行的审批路径
 */
class ParallelNodeHandler extends AbstractNodeHandler
{
    public function getSupportedNodeType(): string
    {
        return 'parallel';
    }

    protected function doHandle(FlowNode $node, array $data, FlowContext $context): void
    {
        // 并行节点处理逻辑
        $parallelBranches = $node->rules['branches'] ?? [];

        if (empty($parallelBranches)) {
            throw new \RuntimeException('并行节点配置错误：缺少分支配置');
        }

        // 创建所有并行分支
        $this->createParallelBranches($parallelBranches, $node, $context);

        // 更新并行节点状态为处理中
        app(\App\Repositories\FlowNodeRepositories::class)->update($node->id, [
            'status' => 'process'
        ]);
    }

    private function createParallelBranches(array $branches, FlowNode $parallelNode, FlowContext $context): void
    {
        $nodeRepository = app(\App\Repositories\FlowNodeRepositories::class);

        foreach ($branches as $branchIndex => $branch) {
            // 为每个分支创建子节点
            $branchNodeData = [
                'flow_id' => $parallelNode->flow_id,
                'name' => $branch['name'] ?? "并行分支 " . ($branchIndex + 1),
                'type' => $branch['type'] ?? 'approval',
                'depth' => $parallelNode->depth + 1,
                'parent_id' => $parallelNode->id,
                'status' => 'process',
                'rules' => $branch['rules'] ?? [],
                'extend' => array_merge($branch['extend'] ?? [], [
                    'branch_index' => $branchIndex,
                    'parallel_parent' => $parallelNode->id
                ])
            ];

            $branchNode = $nodeRepository->store($branchNodeData);

            // 处理分支节点
            $this->handle($branchNode, [], $context);
        }
    }

    /**
     * 检查并行分支是否全部完成
     */
    public function checkParallelCompletion(FlowNode $parallelNode, FlowContext $context): void
    {
        $nodeRepository = app(\App\Repositories\FlowNodeRepositories::class);

        // 获取所有子分支
        $branches = $nodeRepository->query()
            ->where('parent_id', $parallelNode->id)
            ->get();

        // 检查所有分支状态
        $completedBranches = $branches->whereIn('status', ['approve', 'reject'])->count();
        $totalBranches = $branches->count();

        if ($completedBranches === $totalBranches) {
            // 所有分支完成，决定并行节点的最终状态
            $approvedBranches = $branches->where('status', 'approve')->count();
            $rejectedBranches = $branches->where('status', 'reject')->count();

            // 根据并行策略决定最终状态
            $parallelStrategy = $parallelNode->rules['strategy'] ?? 'all_approve';
            $finalStatus = $this->determineParallelStatus($parallelStrategy, $approvedBranches, $rejectedBranches, $totalBranches);

            // 更新并行节点状态
            $nodeRepository->update($parallelNode->id, ['status' => $finalStatus]);

            // 如果通过，继续下一个节点
            if ($finalStatus === 'approve') {
                $this->moveToNextNode($parallelNode, $context);
            } else {
                // 并行节点被驳回，整个流程驳回
                $this->rejectFlow($context);
            }
        }
    }

    private function determineParallelStatus(string $strategy, int $approved, int $rejected, int $total): string
    {
        return match ($strategy) {
            'all_approve' => $approved === $total ? 'approve' : 'reject', // 全部通过
            'any_approve' => $approved > 0 ? 'approve' : 'reject',        // 任一通过
            'majority_approve' => $approved > $total / 2 ? 'approve' : 'reject', // 多数通过
            default => $approved === $total ? 'approve' : 'reject'
        };
    }

    private function moveToNextNode(FlowNode $parallelNode, FlowContext $context): void
    {
        // 获取下一个节点并处理
        $nextNode = $this->getNextNode($parallelNode, $context);

        if ($nextNode) {
            $this->handle($nextNode, [], $context);
        } else {
            // 没有下一个节点，流程完成
            $this->completeFlow($context);
        }
    }

    private function getNextNode(FlowNode $node, FlowContext $context): ?FlowNode
    {
        // 从流程快照中获取下一个节点
        // 这里需要实现具体的节点查找逻辑
        return null;
    }

    private function rejectFlow(FlowContext $context): void
    {
        $flow = $context->getFlow();
        app(\App\Repositories\FlowRepositories::class)->update($flow->id, [
            'status' => 'reject'
        ]);

        $context->setState($context->getRejectState());
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
