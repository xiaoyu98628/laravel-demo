<?php

declare(strict_types=1);

namespace App\Factories;

use App\Constants\Enums\FlowNode\Type;
use App\Strategies\Node\Contracts\NodeTypeInterface;
use App\Strategies\Node\Types\StartNodeStrategy;

/**
 * 节点类型工厂
 * Class NodeTypeFactory
 */
class NodeTypeFactory
{
    public static function make(string $type): NodeTypeInterface
    {
        return match ($type) {
            Type::START->value => app(StartNodeStrategy::class),
            default            => throw new \InvalidArgumentException("不支持的节点类型: {$type}"),
        };
    }
}
