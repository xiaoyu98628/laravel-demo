<?php

declare(strict_types=1);

namespace App\Flow4\Engine;

use App\Flow4\ChainOfResponsibility\NodeHandlerChain;
use App\Flow4\Factory\FlowFactoryInterface;
use App\Models\Flow;
use App\Models\FlowNode;
use App\Repositories\FlowNodeRepositories;
use App\Repositories\FlowRepositories;

/**
 * 审批流程引擎
 * 设计模式：外观模式
 * 作用：提供统一的流程操作接口，封装内部复杂的处理逻辑
 */
class FlowEngine
{
    private FlowFactoryInterface $flowFactory;

    private NodeHandlerChain $nodeHandlerChain;

    private FlowRepositories $flowRepository;

    private FlowNodeRepositories $nodeRepository;

    public function __construct(
        FlowFactoryInterface $flowFactory,
        NodeHandlerChain $nodeHandlerChain,
        FlowRepositories $flowRepository,
        FlowNodeRepositories $nodeRepository
    ) {
        $this->flowFactory      = $flowFactory;
        $this->nodeHandlerChain = $nodeHandlerChain;
        $this->flowRepository   = $flowRepository;
        $this->nodeRepository   = $nodeRepository;
    }

    /**
     * 创建流程
     */
    public function createFlow(array $flowData): Flow
    {
        // 使用工厂模式创建流程
        $flow = $this->flowFactory->createFlow($flowData);

        return $flow;
    }

    /**
     * 启动流程
     */
    public function startFlow(string $flowId, array $data = []): void
    {
        $flow = $this->flowRepository->find($flowId);
        if (! $flow) {
            throw new \InvalidArgumentException("流程不存在: {$flowId}");
        }

        // 创建流程上下文
        $context = new FlowContext($flow);

        // 检查是否可以启动
        if (! $context->canExecuteAction('submit')) {
            throw new \RuntimeException("流程当前状态[{$context->getStateName()}]不允许启动");
        }

        // 创建开始节点
        $startNode = $this->createStartNode($flow, $data);

        // 使用责任链处理开始节点
        $this->nodeHandlerChain->handle($startNode, $data, $context);
    }

    /**
     * 审批流程
     */
    public function approveFlow(string $flowId, string $nodeId, string $approverId, array $data): void
    {
        $flow = $this->flowRepository->find($flowId);
        if (! $flow) {
            throw new \InvalidArgumentException("流程不存在: {$flowId}");
        }

        $node = $this->nodeRepository->find($nodeId);
        if (! $node) {
            throw new \InvalidArgumentException("节点不存在: {$nodeId}");
        }

        // 验证节点属于该流程
        if ($node->flow_id !== $flowId) {
            throw new \InvalidArgumentException('节点不属于指定流程');
        }

        // 创建流程上下文
        $context = new FlowContext($flow);

        // 执行审批操作
        $context->approve($nodeId, $approverId, $data);
    }

    /**
     * 驳回流程
     */
    public function rejectFlow(string $flowId, string $nodeId, string $approverId, array $data): void
    {
        $flow = $this->flowRepository->find($flowId);
        if (! $flow) {
            throw new \InvalidArgumentException("流程不存在: {$flowId}");
        }

        $node = $this->nodeRepository->find($nodeId);
        if (! $node) {
            throw new \InvalidArgumentException("节点不存在: {$nodeId}");
        }

        // 验证节点属于该流程
        if ($node->flow_id !== $flowId) {
            throw new \InvalidArgumentException('节点不属于指定流程');
        }

        // 创建流程上下文
        $context = new FlowContext($flow);

        // 执行驳回操作
        $context->reject($nodeId, $approverId, $data);
    }

    /**
     * 取消流程
     */
    public function cancelFlow(string $flowId): void
    {
        $flow = $this->flowRepository->find($flowId);
        if (! $flow) {
            throw new \InvalidArgumentException("流程不存在: {$flowId}");
        }

        // 创建流程上下文
        $context = new FlowContext($flow);

        // 执行取消操作
        $context->cancel();
    }

    /**
     * 获取流程状态
     */
    public function getFlowStatus(string $flowId): array
    {
        $flow = $this->flowRepository->find($flowId);
        if (! $flow) {
            throw new \InvalidArgumentException("流程不存在: {$flowId}");
        }

        $context = new FlowContext($flow);

        return [
            'flow_id'         => $flowId,
            'status'          => $context->getStateName(),
            'allowed_actions' => $context->getAllowedActions(),
            'current_nodes'   => $this->getCurrentNodes($flowId),
            'flow_info'       => [
                'title'         => $flow->title,
                'business_type' => $flow->business_type,
                'create_time'   => $flow->created_at,
                'creator'       => $flow->create_by,
            ],
        ];
    }

    /**
     * 获取流程的当前活跃节点
     */
    public function getCurrentNodes(string $flowId): array
    {
        $nodes = $this->nodeRepository->query()
            ->where('flow_id', $flowId)
            ->where('status', 'process')
            ->get();

        return $nodes->map(function ($node) {
            return [
                'node_id'           => $node->id,
                'name'              => $node->name,
                'type'              => $node->type,
                'depth'             => $node->depth,
                'rules'             => $node->rules,
                'pending_approvers' => $this->getPendingApprovers($node),
            ];
        })->toArray();
    }

    /**
     * 获取节点的待审批人员
     */
    private function getPendingApprovers(FlowNode $node): array
    {
        // 从节点规则中获取审批人信息
        $approvers = $node->rules['approvers'] ?? [];

        // 获取已处理的审批人
        $taskRepository = app(\App\Repositories\FlowNodeTaskRepositories::class);
        $completedTasks = $taskRepository->query()
            ->where('flow_node_id', $node->id)
            ->whereIn('status', ['approve', 'reject'])
            ->get();

        $completedApprovers = $completedTasks->pluck('approver_id')->toArray();

        // 过滤出待审批人员
        return array_filter($approvers, function ($approver) use ($completedApprovers) {
            return ! in_array($approver['id'] ?? $approver, $completedApprovers);
        });
    }

    /**
     * 创建开始节点
     */
    private function createStartNode(Flow $flow, array $data): FlowNode
    {
        $startNodeData = [
            'flow_id'   => $flow->id,
            'name'      => '开始',
            'type'      => 'start',
            'depth'     => 1,
            'parent_id' => null,
            'status'    => 'process',
            'rules'     => [],
            'extend'    => $data['extend'] ?? [],
        ];

        return $this->nodeRepository->store($startNodeData);
    }

    /**
     * 获取流程历史记录
     */
    public function getFlowHistory(string $flowId): array
    {
        $nodes = $this->nodeRepository->query()
            ->where('flow_id', $flowId)
            ->orderBy('depth')
            ->orderBy('created_at')
            ->get();

        $taskRepository = app(\App\Repositories\FlowNodeTaskRepositories::class);

        return $nodes->map(function ($node) use ($taskRepository) {
            $tasks = $taskRepository->query()
                ->where('flow_node_id', $node->id)
                ->get();

            return [
                'node_id'    => $node->id,
                'name'       => $node->name,
                'type'       => $node->type,
                'status'     => $node->status,
                'depth'      => $node->depth,
                'created_at' => $node->created_at,
                'tasks'      => $tasks->map(function ($task) {
                    return [
                        'task_id'       => $task->id,
                        'approver_id'   => $task->approver_id,
                        'approver_name' => $task->approver_name,
                        'status'        => $task->status,
                        'comment'       => $task->comment,
                        'handled_at'    => $task->handled_at,
                        'created_at'    => $task->created_at,
                    ];
                })->toArray(),
            ];
        })->toArray();
    }

    /**
     * 重新提交被驳回的流程
     */
    public function resubmitFlow(string $flowId, array $data = []): void
    {
        $flow = $this->flowRepository->find($flowId);
        if (! $flow) {
            throw new \InvalidArgumentException("流程不存在: {$flowId}");
        }

        $context = new FlowContext($flow);

        // 检查是否可以重新提交
        if (! $context->canExecuteAction('resubmit')) {
            throw new \RuntimeException("流程当前状态[{$context->getStateName()}]不允许重新提交");
        }

        // 重置流程状态
        $this->resetFlowForResubmit($flow, $data);

        // 重新启动流程
        $this->startFlow($flowId, $data);
    }

    /**
     * 重置流程以便重新提交
     */
    private function resetFlowForResubmit(Flow $flow, array $data): void
    {
        // 删除原有的节点和任务
        $this->nodeRepository->query()
            ->where('flow_id', $flow->id)
            ->delete();

        $taskRepository = app(\App\Repositories\FlowNodeTaskRepositories::class);
        $taskRepository->query()
            ->where('flow_id', $flow->id)
            ->delete();

        // 更新流程状态
        $this->flowRepository->update($flow->id, [
            'status' => 'create',
            'extend' => array_merge($flow->extend ?? [], $data['extend'] ?? []),
        ]);
    }

    /**
     * 转发流程节点给其他人处理
     */
    public function forwardNode(string $flowId, string $nodeId, string $fromUserId, string $toUserId, string $comment = ''): void
    {
        $flow = $this->flowRepository->find($flowId);
        if (! $flow) {
            throw new \InvalidArgumentException("流程不存在: {$flowId}");
        }

        $node = $this->nodeRepository->find($nodeId);
        if (! $node || $node->flow_id !== $flowId) {
            throw new \InvalidArgumentException('节点不存在或不属于该流程');
        }

        // 检查节点是否支持转发
        if (! in_array($node->type, ['approval'])) {
            throw new \RuntimeException('该类型的节点不支持转发');
        }

        // 创建转发任务
        $taskRepository = app(\App\Repositories\FlowNodeTaskRepositories::class);
        $taskData       = [
            'flow_id'       => $flowId,
            'flow_node_id'  => $nodeId,
            'approver_id'   => $toUserId,
            'approver_name' => $this->getUserName($toUserId),
            'status'        => 'process',
            'comment'       => "转发自 {$this->getUserName($fromUserId)}: {$comment}",
            'extend'        => [
                'forwarded_from'  => $fromUserId,
                'forward_comment' => $comment,
            ],
        ];

        $taskRepository->store($taskData);

        // 记录转发日志
        \Log::info('流程节点转发', [
            'flow_id'   => $flowId,
            'node_id'   => $nodeId,
            'from_user' => $fromUserId,
            'to_user'   => $toUserId,
            'comment'   => $comment,
        ]);
    }

    /**
     * 获取用户姓名（这里应该从用户服务获取）
     */
    private function getUserName(string $userId): string
    {
        // 这里应该调用用户服务获取用户姓名
        // 临时返回用户ID
        return "User_{$userId}";
    }

    /**
     * 批量审批
     */
    public function batchApprove(array $approvals): array
    {
        $results = [];

        foreach ($approvals as $approval) {
            try {
                $this->approveFlow(
                    $approval['flow_id'],
                    $approval['node_id'],
                    $approval['approver_id'],
                    $approval['data'] ?? []
                );

                $results[] = [
                    'flow_id' => $approval['flow_id'],
                    'success' => true,
                    'message' => '审批成功',
                ];
            } catch (\Exception $e) {
                $results[] = [
                    'flow_id' => $approval['flow_id'],
                    'success' => false,
                    'message' => $e->getMessage(),
                ];
            }
        }

        return $results;
    }

    /**
     * 获取流程统计信息
     */
    public function getFlowStatistics(array $filters = []): array
    {
        $query = $this->flowRepository->query();

        // 应用过滤条件
        if (isset($filters['business_type'])) {
            $query->where('business_type', $filters['business_type']);
        }
        if (isset($filters['creator'])) {
            $query->where('create_by', $filters['creator']);
        }
        if (isset($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }
        if (isset($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }

        // 统计各状态的流程数量
        $statusCounts = $query->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // 统计业务类型分布
        $businessTypeCounts = $this->flowRepository->query()
            ->selectRaw('business_type, COUNT(*) as count')
            ->groupBy('business_type')
            ->pluck('count', 'business_type')
            ->toArray();

        return [
            'total_flows'                => array_sum($statusCounts),
            'status_distribution'        => $statusCounts,
            'business_type_distribution' => $businessTypeCounts,
            'average_completion_time'    => $this->calculateAverageCompletionTime($filters),
            'pending_approvals'          => $this->getPendingApprovalsCount($filters),
        ];
    }

    /**
     * 计算平均完成时间
     */
    private function calculateAverageCompletionTime(array $filters): float
    {
        $query = $this->flowRepository->query()
            ->where('status', 'success')
            ->whereNotNull('end_time');

        // 应用过滤条件
        if (isset($filters['business_type'])) {
            $query->where('business_type', $filters['business_type']);
        }
        if (isset($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }
        if (isset($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }

        $flows = $query->get(['created_at', 'end_time']);

        if ($flows->isEmpty()) {
            return 0;
        }

        $totalHours = $flows->sum(function ($flow) {
            return $flow->created_at->diffInHours($flow->end_time);
        });

        return $totalHours / $flows->count();
    }

    /**
     * 获取待处理审批数量
     */
    private function getPendingApprovalsCount(array $filters): int
    {
        $taskRepository = app(\App\Repositories\FlowNodeTaskRepositories::class);

        $query = $taskRepository->query()
            ->where('status', 'process');

        // 如果有业务类型过滤，需要关联流程表
        if (isset($filters['business_type']) || isset($filters['date_from']) || isset($filters['date_to'])) {
            $query->join('flows', 'flow_node_tasks.flow_id', '=', 'flows.id');

            if (isset($filters['business_type'])) {
                $query->where('flows.business_type', $filters['business_type']);
            }
            if (isset($filters['date_from'])) {
                $query->where('flows.created_at', '>=', $filters['date_from']);
            }
            if (isset($filters['date_to'])) {
                $query->where('flows.created_at', '<=', $filters['date_to']);
            }
        }

        return $query->count();
    }
}
