<?php

declare(strict_types=1);

namespace App\Flow4\State\States;

use App\Flow4\Engine\FlowContext;
use App\Flow4\State\AbstractFlowState;

/**
 * 处理状态 - 流程正在审批中的状态
 * 设计模式：状态模式
 * 应用场景：处理正在进行的审批流程，支持审批、驳回、取消操作
 */
class ProcessState extends AbstractFlowState
{
    public function approve(string $flowId, string $nodeId, string $approverId, array $data, FlowContext $context): void
    {
        $this->checkPermission('approve');

        // 处理审批逻辑
        $this->processApproval($flowId, $nodeId, $approverId, $data, $context);
    }

    public function reject(string $flowId, string $nodeId, string $approverId, array $data, FlowContext $context): void
    {
        $this->checkPermission('reject');

        // 处理驳回逻辑
        $this->processRejection($flowId, $nodeId, $approverId, $data, $context);

        // 转换到驳回状态
        $this->changeState($context, $context->getRejectState(), $flowId, 'reject');
    }

    public function getStateName(): string
    {
        return 'process';
    }

    public function getAllowedActions(): array
    {
        return ['approve', 'reject', 'cancel'];
    }

    private function processApproval(string $flowId, string $nodeId, string $approverId, array $data, FlowContext $context): void
    {
        // 更新节点任务状态
        $taskRepository = app(\App\Repositories\FlowNodeTaskRepositories::class);
        $nodeRepository = app(\App\Repositories\FlowNodeRepositories::class);

        // 创建或更新审批任务
        $taskData = [
            'approver_id'    => $approverId,
            'approver_name'  => $data['approver_name']  ?? '',
            'approver_type'  => $data['approver_type']  ?? 'user',
            'operation_info' => $data['operation_info'] ?? [],
            'status'         => 'approve',
            'flow_node_id'   => $nodeId,
            'extend'         => $data['extend'] ?? [],
        ];

        $existingTask = $taskRepository->query()
            ->where('flow_node_id', $nodeId)
            ->where('approver_id', $approverId)
            ->first();

        if ($existingTask) {
            $taskRepository->update($existingTask->id, $taskData);
        } else {
            $taskRepository->store($taskData);
        }

        // 检查节点是否完成
        $this->checkNodeCompletion($nodeId, $context);
    }

    private function processRejection(string $flowId, string $nodeId, string $approverId, array $data, FlowContext $context): void
    {
        $taskRepository = app(\App\Repositories\FlowNodeTaskRepositories::class);

        // 创建驳回任务
        $taskData = [
            'approver_id'    => $approverId,
            'approver_name'  => $data['approver_name']  ?? '',
            'approver_type'  => $data['approver_type']  ?? 'user',
            'operation_info' => $data['operation_info'] ?? [],
            'status'         => 'reject',
            'flow_node_id'   => $nodeId,
            'extend'         => $data['extend'] ?? [],
        ];

        $taskRepository->store($taskData);

        // 更新节点状态为驳回
        $nodeRepository = app(\App\Repositories\FlowNodeRepositories::class);
        $nodeRepository->update($nodeId, ['status' => 'reject']);
    }

    private function checkNodeCompletion(string $nodeId, FlowContext $context): void
    {
        // 检查当前节点是否已完成
        // 如果完成，流转到下一个节点或完成整个流程

        $nodeRepository = app(\App\Repositories\FlowNodeRepositories::class);
        $node           = $nodeRepository->find($nodeId);

        if (! $node) {
            return;
        }

        // 根据节点规则判断是否完成
        $approvalMethod = $node->rules['approval_method'] ?? 'any';
        $isCompleted    = $this->isNodeCompleted($node, $approvalMethod);

        if ($isCompleted) {
            $nodeRepository->update($nodeId, ['status' => 'approve']);

            // 检查是否还有下一个节点
            $hasNextNode = $this->hasNextNode($node);

            if (! $hasNextNode) {
                // 没有下一个节点，流程完成
                $flow = $context->getFlow();
                $this->changeState($context, $context->getSuccessState(), $flow->id, 'success');
            }
        }
    }

    private function isNodeCompleted(\App\Models\FlowNode $node, string $approvalMethod): bool
    {
        $taskRepository = app(\App\Repositories\FlowNodeTaskRepositories::class);
        $tasks          = $taskRepository->query()->where('flow_node_id', $node->id)->get();

        $approvedCount = $tasks->where('status', 'approve')->count();
        $rejectedCount = $tasks->where('status', 'reject')->count();
        $totalCount    = $tasks->count();

        if ($rejectedCount > 0) {
            return false; // 有驳回就不通过
        }

        return match ($approvalMethod) {
            'all'   => $approvedCount === $totalCount, // 会签：所有人都同意
            'any'   => $approvedCount > 0, // 或签：任一人同意
            default => $approvedCount > 0
        };
    }

    private function hasNextNode(\App\Models\FlowNode $node): bool
    {
        // 从快照中检查是否还有下一个节点
        // 这里需要实现具体的节点查找逻辑
        return false;
    }
}
