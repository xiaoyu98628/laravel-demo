<?php

declare(strict_types=1);

namespace other\Flow4\ChainOfResponsibility;

use App\Models\FlowNode;
use other\Flow4\Engine\FlowContext;

/**
 * 节点处理器接口
 * 设计模式：责任链模式
 * 作用：定义审批节点的处理接口，实现节点处理逻辑的解耦
 */
interface NodeHandlerInterface
{
    /**
     * 处理节点
     */
    public function handle(FlowNode $node, array $data, FlowContext $context): void;

    /**
     * 设置下一个处理器
     */
    public function setNext(NodeHandlerInterface $handler): NodeHandlerInterface;

    /**
     * 获取支持的节点类型
     */
    public function getSupportedNodeType(): string;
}
