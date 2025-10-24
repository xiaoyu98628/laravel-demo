<?php

declare(strict_types=1);

namespace App\Flow4\Strategy;

use App\Constants\Enums\FlowNode\Mode;
use App\Constants\Enums\FlowNodeTask\Status as TaskStatus;
use App\Models\Flow;
use App\Models\FlowNode;
use App\Repositories\FlowNodeRepositories;
use App\Repositories\FlowNodeTaskRepositories;

/**
 * 抽象审批策略
 * 实现通用的审批处理逻辑
 */
abstract class AbstractApprovalStrategy implements ApprovalStrategyInterface
{
    protected FlowNodeRepositories $nodeRepository;
    protected FlowNodeTaskRepositories $taskRepository;

    public function __construct(
        FlowNodeRepositories $nodeRepository,
        FlowNodeTaskRepositories $taskRepository
    ) {
        $this->nodeRepository = $nodeRepository;
        $this->taskRepository = $taskRepository;
    }

    /**
     * 通用验证逻辑
     */
    public function validate(Flow $flow, FlowNode $node, array $data): bool
    {
        // 检查流程状态
        if ($flow->status !== 'process') {
            return false;
        }

        // 检查节点状态
        if ($node->status !== 'process') {
            return false;
        }

        // 检查审批人权限
        return $this->validateApproverPermission($node, $data);
    }

    /**
     * 处理会签逻辑 - 所有人都同意才通过
     */
    public function handleCountersign(FlowNode $node, array $approvers): bool
    {
        $tasks = $this->taskRepository->query()
            ->where('flow_node_id', $node->id)
            ->get();

        $approvedCount = $tasks->where('status', TaskStatus::APPROVE->value)->count();
        $rejectedCount = $tasks->where('status', TaskStatus::REJECT->value)->count();

        // 如果有人驳回，整个节点驳回
        if ($rejectedCount > 0) {
            return false;
        }

        // 所有人都同意才通过
        return $approvedCount === $tasks->count();
    }

    /**
     * 处理并签逻辑 - 任意一人同意即通过
     */
    public function handleParallelSign(FlowNode $node, array $approvers): bool
    {
        $tasks = $this->taskRepository->query()
            ->where('flow_node_id', $node->id)
            ->get();

        $approvedCount = $tasks->where('status', TaskStatus::APPROVE->value)->count();
        $rejectedCount = $tasks->where('status', TaskStatus::REJECT->value)->count();
        $totalCount = $tasks->count();

        // 如果所有人都驳回，节点驳回
        if ($rejectedCount === $totalCount) {
            return false;
        }

        // 任意一人同意即通过
        return $approvedCount > 0;
    }

    /**
     * 验证审批人权限 - 子类可重写
     */
    protected function validateApproverPermission(FlowNode $node, array $data): bool
    {
        $approverId = $data['approver_id'] ?? '';

        // 检查审批人是否在节点的审批人列表中
        $rules = $node->rules ?? [];
        $approvers = $rules['approvers'] ?? [];

        return in_array($approverId, array_column($approvers, 'id'));
    }

    /**
     * 获取下一个节点的通用逻辑
     */
    public function getNextNode(Flow $flow, FlowNode $currentNode): ?FlowNode
    {
        // 从流程快照中找到下一个节点
        $snapshot = $flow->flow_node_template_snapshot ?? [];
        return $this->findNextNodeFromSnapshot($snapshot, $currentNode);
    }

    /**
     * 从快照中查找下一个节点
     */
    protected function findNextNodeFromSnapshot(array $snapshot, FlowNode $currentNode): ?FlowNode
    {
        // 根据当前节点的深度和父级关系找到下一个节点
        // 这里需要实现具体的节点查找逻辑
        return null;
    }
}
