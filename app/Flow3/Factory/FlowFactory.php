<?php

declare(strict_types=1);

namespace App\Flow3\Factory;

use App\Constants\Enums\Flow\BusinessType;
use App\Flow3\Contracts\FlowTypeStrategyInterface;
use App\Flow3\Strategies\Flow\Types\FinanceStrategy;

readonly class FlowFactory
{
    public function __construct(
        private FinanceStrategy $financeStrategy,
    ) {}

    /**
     * @param  string  $type
     * @return FlowTypeStrategyInterface
     * @throws \Exception
     */
    public function make(string $type): FlowTypeStrategyInterface
    {
        return match ($type) {
            BusinessType::FINANCE->value => $this->financeStrategy,
            default                      => throw new \Exception('未知的审批类型'),
        };
    }
}
