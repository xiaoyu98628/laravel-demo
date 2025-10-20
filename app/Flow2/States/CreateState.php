<?php

declare(strict_types=1);

namespace App\Flow2\States;

use App\Constants\Enums\Flow\Status;
use App\Flow2\Contracts\FlowStateInterface;
use App\Models\Flow;

final class CreateState implements FlowStateInterface
{
    public function enter(Flow $flow): void
    {
        $flow->status = Status::CREATE->value;
        $flow->save();
    }

    public function toProcess(Flow $flow): void
    {
        $flow->status = Status::PROCESS->value;
        $flow->save();
    }

    public function toSuccess(Flow $flow): void
    { /* ignore */
    }

    public function toReject(Flow $flow): void
    { /* ignore */
    }

    public function toCancel(Flow $flow): void
    {
        $flow->status = Status::CANCEL->value;
        $flow->save();
    }
}
