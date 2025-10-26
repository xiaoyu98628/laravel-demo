<?php

declare(strict_types=1);

namespace other\Flow2\Factory;

use InvalidArgumentException;
use other\Flow2\Contracts\ApprovalTypeStrategyInterface;
use other\Flow2\Strategies\Types\FinanceApprovalStrategy;
use other\Flow2\Strategies\Types\ProjectApprovalStrategy;

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
