<?php

declare(strict_types=1);

namespace other\Flow2\Engine;

use App\Constants\Enums\Flow\Status;
use App\Constants\Enums\FlowNodeTask\Status as FlowNodeTaskStatus;
use App\Models\Flow;
use App\Models\FlowNode;
use other\Flow2\Factory\NodeHandlerFactory;

final class FlowEngine
{
    public function __construct(private readonly NodeHandlerFactory $handlerFactory) {}

    // 启动或继续推进（自动节点可被批量“吃掉”直到遇到审批节点）
    public function bootOrAdvance(Flow $flow): void
    {
        $snapshot  = $flow->flow_node_template_snapshot;
        $navigator = new FlowNavigator($snapshot);

        // 指针初始化
        $extend = $flow->extend ?? [];
        if (empty($extend['ptr_tpl_node_id'])) {
            $extend['ptr_tpl_node_id'] = $snapshot['id']; // 根（start）
            $extend['depth_counter']   = 0;
            $flow->extend              = $extend;
            $flow->save();
        }

        // 循环推进自动节点
        while (true) {
            $tpl = $navigator->find($flow->extend['ptr_tpl_node_id']);
            if (! $tpl) {
                throw new \RuntimeException('模板指针无效');
            }

            // 如果当前没有实例，则实例化
            $currentNode = $this->ensureNodeInstance($flow, $tpl);
            // 交给对应处理器处理（start/cc/condition...会自动通过）
            $this->dispatchNode($flow, $currentNode);

            // 根据节点结果决定是否继续推进
            if (in_array($currentNode->status, [FlowNodeTaskStatus::APPROVE->value, FlowNodeTaskStatus::AUTO->value, FlowNodeTaskStatus::SKIP->value], true)) {
                // 找下一个模板节点
                $nextTpl = (new FlowNavigator($snapshot))->next($tpl, $flow->business_snapshot ?? []);
                if (! $nextTpl) {
                    // 没有下一个，流程完成
                    $flow->status = Status::SUCCESS->value;
                    $flow->save();
                    break;
                }
                // 移动指针
                $extend                    = $flow->extend ?? [];
                $extend['ptr_tpl_node_id'] = $nextTpl['id'];
                $flow->extend              = $extend;
                $flow->save();

                // 继续 while 循环，可能还是自动节点
                continue;
            }

            if ($currentNode->status === FlowNodeTaskStatus::REJECT->value) {
                $flow->status = Status::REJECT->value;
                $flow->save();
                break;
            }

            // 走到这里说明是需要人工处理的审批节点（process），暂停等待操作
            break;
        }
    }

    // 审批任务完成后调用（同意/驳回完成后继续推进）
    public function afterNodeSettled(Flow $flow, FlowNode $node): void
    {
        if (in_array($node->status, [FlowNodeTaskStatus::APPROVE->value, FlowNodeTaskStatus::AUTO->value, FlowNodeTaskStatus::SKIP->value], true)) {
            $this->bootOrAdvance($flow);
        } elseif ($node->status === FlowNodeTaskStatus::REJECT->value) {
            $flow->status = Status::REJECT->value;
            $flow->save();
        }
    }

    // 驳回到指定模板节点（reopen）
    public function rejectToTemplateNode(Flow $flow, string $targetTplNodeId, string $comment = ''): void
    {
        // 取消当前节点（若存在）
        $currentId = $flow->extend['current_node_id'] ?? null;
        if ($currentId) {
            FlowNode::query()->where('id', $currentId)->update(['status' => FlowNodeTaskStatus::CANCEL->value]);
        }

        // 移动指针到目标模板节点
        $extend                    = $flow->extend ?? [];
        $extend['ptr_tpl_node_id'] = $targetTplNodeId;
        $flow->extend              = $extend;
        $flow->status              = Status::PROCESS->value;
        $flow->save();

        // 重新从目标节点实例化并推进（自动节点会被连续处理）
        $this->bootOrAdvance($flow);
    }

    private function ensureNodeInstance(Flow $flow, array $tplNode): FlowNode
    {
        $extend    = $flow->extend              ?? [];
        $currentId = $extend['current_node_id'] ?? null;
        if ($currentId) {
            $existing = FlowNode::query()->find($currentId);
            if ($existing && $existing->type === $tplNode['type'] && $existing->name === ($tplNode['name'] ?? '')) {
                return $existing;
            }
        }

        // 创建新节点实例（审批一个创建一个）
        $depth = intval($extend['depth_counter'] ?? 0) + 1;
        $node  = FlowNode::query()->create([
            'parent_id' => null, // 可选：保存上一实例ID
            'depth'     => $depth,
            'name'      => $tplNode['name'] ?? ucfirst($tplNode['type']),
            'type'      => $tplNode['type'],
            'rules'     => $tplNode['rules'] ?? [],
            'status'    => FlowNodeTaskStatus::PROCESS->value,
            'callback'  => $tplNode['callback'] ?? null,
            'flow_id'   => $flow->id,
            'extend'    => ['tpl_node_id' => $tplNode['id']],
        ]);

        // 更新指针
        $extend['current_node_id'] = $node->id;
        $extend['depth_counter']   = $depth;
        $flow->extend              = $extend;
        $flow->save();

        return $node;
    }

    private function dispatchNode(Flow $flow, FlowNode $node): void
    {
        foreach ($this->handlerFactory->all() as $handler) {
            if ($handler->supports($node->type)) {
                $handler->handle($flow, $node);
                break;
            }
        }
    }
}
