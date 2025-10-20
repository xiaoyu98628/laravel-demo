<?php

declare(strict_types=1);

namespace App\Flow2\Factory;

use App\Flow2\Contracts\ActionStrategyInterface;
use App\Flow2\Strategies\Actions\AgreeAction;
use App\Flow2\Strategies\Actions\CcAction;
use App\Flow2\Strategies\Actions\RejectAction;
use App\Flow2\Strategies\Actions\RejectToAction;
use InvalidArgumentException;

final class ActionStrategyFactory
{
    public function __construct(
        private readonly AgreeAction $agree,
        private readonly RejectAction $reject,
        private readonly CcAction $cc,
        private readonly RejectToAction $rejectToAction,
    ) {}

    public function for(string $action): ActionStrategyInterface
    {
        return match ($action) {
            'agree'     => $this->agree,
            'reject'    => $this->reject,
            'cc'        => $this->cc,
            'reject_to' => $this->rejectToAction,
            default     => throw new InvalidArgumentException('不支持的动作：'.$action),
        };
    }
}
