<?php

declare(strict_types=1);

namespace App\Flow2\Engine;

use App\Flow2\Contracts\FlowStateInterface;
use App\Models\Flow;

final class FlowContext
{
    public function __construct(private Flow $flow, private FlowStateInterface $state) {}

    public function setState(FlowStateInterface $state): void
    {
        $this->state = $state;
    }

    public function getFlow(): Flow
    {
        return $this->flow;
    }

    public function enter(): void
    {
        $this->state->enter($this->flow);
    }

    public function toProcess(): void
    {
        $this->state->toProcess($this->flow);
    }

    public function toSuccess(): void
    {
        $this->state->toSuccess($this->flow);
    }

    public function toReject(): void
    {
        $this->state->toReject($this->flow);
    }

    public function toCancel(): void
    {
        $this->state->toCancel($this->flow);
    }
}
