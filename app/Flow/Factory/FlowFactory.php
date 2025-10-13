<?php

declare(strict_types=1);

namespace App\Flow\Factory;

use App\Flow\Contracts\ApprovalTypeStrategyInterface;
use App\Flow\Strategies\Types\FinanceApprovalStrategy;
use App\Flow\Strategies\Types\ProjectApprovalStrategy;
use InvalidArgumentException;

final class FlowFactory
{
    public function __construct(
        private readonly FinanceApprovalStrategy $finance,
        private readonly ProjectApprovalStrategy $project,
    ) {}

    public function for(string $type): ApprovalTypeStrategyInterface
    {
        return match ($type) {
            'finance' => $this->finance,
            'project' => $this->project,
            default   => throw new InvalidArgumentException('不支持的业务类型：'.$type),
        };
    }
}
