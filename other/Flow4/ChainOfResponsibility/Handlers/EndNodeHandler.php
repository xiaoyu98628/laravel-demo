<?php

declare(strict_types=1);

namespace other\Flow4\ChainOfResponsibility\Handlers;

use App\Models\FlowNode;
use other\Flow4\ChainOfResponsibility\AbstractNodeHandler;
use other\Flow4\Engine\FlowContext;

/**
 * 结束节点处理器
 * 设计模式：责任链模式
 * 应用场景：处理流程的结束节点，完成流程
 */
class EndNodeHandler extends AbstractNodeHandler
{
    public function getSupportedNodeType(): string
    {
        return 'end';
    }

    protected function doHandle(FlowNode $node, array $data, FlowContext $context): void
    {
        // 结束节点处理逻辑
        $flow = $context->getFlow();

        // 创建结束节点记录
        $nodeRepository = app(\App\Repositories\FlowNodeRepositories::class);
        $endNodeData = [
            'flow_id' => $flow->id,
            'name' => $node->name ?? '结束',
            'type' => 'end',
            'depth' => $this->calculateDepth($flow->id),
            'parent_id' => $this->getLastNodeId($flow->id),
            'status' => 'approve', // 结束节点默认通过
            'rules' => [],
            'extend' => $data['extend'] ?? []
        ];

        $endNode = $nodeRepository->store($endNodeData);

        // 完成整个流程
        $this->completeFlow($flow, $context);

        // 触发流程完成后的业务逻辑
        $this->triggerCompletionCallbacks($flow, $context);
    }

    private function calculateDepth(string $flowId): int
    {
        $nodeRepository = app(\App\Repositories\FlowNodeRepositories::class);
        $maxDepth = $nodeRepository->query()
            ->where('flow_id', $flowId)
            ->max('depth');

        return ($maxDepth ?? 0) + 1;
    }

    private function getLastNodeId(string $flowId): ?string
    {
        $nodeRepository = app(\App\Repositories\FlowNodeRepositories::class);
        $lastNode = $nodeRepository->query()
            ->where('flow_id', $flowId)
            ->orderBy('depth', 'desc')
            ->first();

        return $lastNode?->id;
    }

    private function completeFlow(\App\Models\Flow $flow, FlowContext $context): void
    {
        // 更新流程状态为成功
        app(\App\Repositories\FlowRepositories::class)->update($flow->id, [
            'status' => 'success',
            'end_time' => now()
        ]);

        // 转换状态
        $context->setState($context->getSuccessState());
    }

    private function triggerCompletionCallbacks(\App\Models\Flow $flow, FlowContext $context): void
    {
        // 触发流程完成后的回调
        // 可以发送通知、更新关联数据等

        try {
            // 发送完成通知
            $this->sendCompletionNotification($flow);

            // 更新业务数据
            $this->updateBusinessData($flow);

            // 记录完成日志
            $this->logFlowCompletion($flow);

        } catch (\Exception $e) {
            // 记录错误，但不影响流程状态
            \Log::error('流程完成回调执行失败', [
                'flow_id' => $flow->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    private function sendCompletionNotification(\App\Models\Flow $flow): void
    {
        // 发送完成通知给相关人员
        // 这里可以使用事件系统或通知系统
    }

    private function updateBusinessData(\App\Models\Flow $flow): void
    {
        // 根据业务类型更新相关业务数据
        $businessType = $flow->business_type;

        // 使用工厂模式获取对应的业务处理器
        $handlerClass = "App\\Flow\\Business\\Handlers\\" . ucfirst($businessType) . "CompletionHandler";

        if (class_exists($handlerClass)) {
            $handler = app($handlerClass);
            $handler->handle($flow);
        }
    }

    private function logFlowCompletion(\App\Models\Flow $flow): void
    {
        \Log::info('审批流程完成', [
            'flow_id' => $flow->id,
            'business_type' => $flow->business_type,
            'title' => $flow->title,
            'creator' => $flow->create_by,
            'completion_time' => now()->toDateTimeString()
        ]);
    }
}
