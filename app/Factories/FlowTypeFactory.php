<?php

declare(strict_types=1);

namespace App\Factories;

use App\Constants\Enums\Flow\Type;
use App\Strategies\Flow\Contracts\FlowTypeInterface;
use App\Strategies\Flow\Types\GeneralFlowStrategy;

/**
 * 审批流类型工厂
 * Class FlowTypeFactory
 */
class FlowTypeFactory
{
    public function make(string $type): FlowTypeInterface
    {
        return match ($type) {
            Type::GENERAL->value => app(GeneralFlowStrategy::class),
            default              => throw new \InvalidArgumentException("不支持的流程类型: {$type}"),
        };
    }
}
