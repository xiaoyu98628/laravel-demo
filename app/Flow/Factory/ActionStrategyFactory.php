<?php

declare(strict_types=1);

namespace App\Flow\Factory;

use App\Flow\Contracts\ActionStrategyInterface;
use App\Flow\Strategies\Actions\AgreeAction;
use App\Flow\Strategies\Actions\CcAction;
use App\Flow\Strategies\Actions\RejectAction;
use App\Flow\Strategies\Actions\RejectToAction;
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
