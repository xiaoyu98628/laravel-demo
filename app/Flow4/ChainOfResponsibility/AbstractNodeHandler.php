<?php

declare(strict_types=1);

namespace App\Flow4\ChainOfResponsibility;

use App\Flow4\Engine\FlowContext;
use App\Models\FlowNode;

/**
 * 抽象节点处理器
 * 实现责任链的基础逻辑
 */
abstract class AbstractNodeHandler implements NodeHandlerInterface
{
    private ?NodeHandlerInterface $nextHandler = null;

    public function setNext(NodeHandlerInterface $handler): NodeHandlerInterface
    {
        $this->nextHandler = $handler;

        return $handler;
    }

    public function handle(FlowNode $node, array $data, FlowContext $context): void
    {
        // 检查是否能处理当前节点类型
        if ($this->canHandle($node)) {
            $this->doHandle($node, $data, $context);
        } elseif ($this->nextHandler) {
            $this->nextHandler->handle($node, $data, $context);
        } else {
            throw new \RuntimeException("没有找到能处理节点类型 [{$node->type}] 的处理器");
        }
    }

    /**
     * 检查是否能处理该节点
     */
    protected function canHandle(FlowNode $node): bool
    {
        return $node->type === $this->getSupportedNodeType();
    }

    /**
     * 具体的处理逻辑 - 子类必须实现
     */
    abstract protected function doHandle(FlowNode $node, array $data, FlowContext $context): void;
}
