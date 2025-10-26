<?php

declare(strict_types=1);

namespace App\Factories;

use App\Constants\Enums\FlowNode\Mode;
use App\Strategies\Approval\Contracts\ApprovalModeInterface;
use App\Strategies\Approval\Modes\AllApprovalStrategy;
use App\Strategies\Approval\Modes\AnyApprovalStrategy;
use App\Strategies\Approval\Modes\SequentialApprovalStrategy;

/**
 * 审批方式策略工厂
 * Class ApprovalStrategyFactory
 */
class ApprovalStrategyFactory
{
    public function make(string $type): ApprovalModeInterface
    {
        return match ($type) {
            Mode::All->value        => app(AllApprovalStrategy::class),
            Mode::Any->value        => app(AnyApprovalStrategy::class),
            Mode::SEQUENTIAL->value => app(SequentialApprovalStrategy::class),
            default                 => throw new \InvalidArgumentException("不支持的审批模式: {$type}"),
        };
    }
}
