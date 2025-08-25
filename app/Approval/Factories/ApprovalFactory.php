<?php

declare(strict_types=1);

namespace App\Approval\Factories;

use App\Approval\Strategy\{
    ApprovalStrategyInterface,
    PartnerStrategy,
    PublisherStrategy,
    FinanceStrategy,
    ExecutionStrategy,
    WorkflowStrategy,
    ProjectStrategy
};

class ApprovalFactory
{
    public static function make(string $flowCode): ApprovalStrategyInterface
    {
        return match ($flowCode) {
            'partner'   => new PartnerStrategy(),
            'publisher' => new PublisherStrategy(),
            'finance'   => new FinanceStrategy(),
            'execution' => new ExecutionStrategy(),
            'workflow'  => new WorkflowStrategy(),
            'project'   => new ProjectStrategy(),
            default     => throw new \InvalidArgumentException("Unknown flow code: $flowCode"),
        };
    }
}
