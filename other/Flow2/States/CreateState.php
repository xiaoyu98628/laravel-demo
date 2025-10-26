<?php

declare(strict_types=1);

namespace other\Flow2\States;

use App\Constants\Enums\Flow\Status;
use App\Models\Flow;
use other\Flow2\Contracts\FlowStateInterface;

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
