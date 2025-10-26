<?php

declare(strict_types=1);

namespace other\Flow4\ChainOfResponsibility;

use App\Models\FlowNode;
use other\Flow4\ChainOfResponsibility\Handlers\ApprovalNodeHandler;
use other\Flow4\ChainOfResponsibility\Handlers\ConditionNodeHandler;
use other\Flow4\ChainOfResponsibility\Handlers\EndNodeHandler;
use other\Flow4\ChainOfResponsibility\Handlers\ParallelNodeHandler;
use other\Flow4\ChainOfResponsibility\Handlers\StartNodeHandler;
use other\Flow4\Engine\FlowContext;

/**
 * 节点处理器链管理器
 * 设计模式：责任链模式
 * 作用：管理和组织所有的节点处理器，提供统一的处理入口
 */
class NodeHandlerChain
{
    private NodeHandlerInterface $firstHandler;

    public function __construct()
    {
        $this->buildHandlerChain();
    }

    /**
     * 构建处理器链
     */
    private function buildHandlerChain(): void
    {
        // 创建各种处理器实例
        $startHandler     = app(StartNodeHandler::class);
        $approvalHandler  = app(ApprovalNodeHandler::class);
        $conditionHandler = app(ConditionNodeHandler::class);
        $parallelHandler  = app(ParallelNodeHandler::class);
        $endHandler       = app(EndNodeHandler::class);

        // 构建责任链
        $startHandler
            ->setNext($approvalHandler)
            ->setNext($conditionHandler)
            ->setNext($parallelHandler)
            ->setNext($endHandler);

        $this->firstHandler = $startHandler;
    }

    /**
     * 处理节点
     */
    public function handle(FlowNode $node, array $data, FlowContext $context): void
    {
        $this->firstHandler->handle($node, $data, $context);
    }

    /**
     * 添加自定义处理器到链中
     */
    public function addHandler(NodeHandlerInterface $handler): void
    {
        // 将新处理器插入到链的开头
        $handler->setNext($this->firstHandler);
        $this->firstHandler = $handler;
    }

    /**
     * 获取支持的节点类型列表
     */
    public function getSupportedNodeTypes(): array
    {
        $types   = [];
        $current = $this->firstHandler;

        while ($current !== null) {
            $types[] = $current->getSupportedNodeType();
            // 这里需要获取下一个处理器，但接口中没有getNext方法
            // 可以通过反射或者修改接口设计来实现
            break; // 暂时只返回第一个
        }

        return $types;
    }
}
