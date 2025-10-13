<?php

declare(strict_types=1);

namespace App\Flow\Factory;

use App\Flow\Contracts\NodeHandlerInterface;

final class NodeHandlerFactory
{
    /**
     * 返回所有可用的节点处理器，按优先级排列
     * @param  NodeHandlerInterface[]  $handlers
     */
    public function __construct(private readonly array $handlers) {}

    /** @return NodeHandlerInterface[] */
    public function all(): array
    {
        return $this->handlers;
    }
}
