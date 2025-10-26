<?php
declare(strict_types=1);

namespace other\Flow2\States;

use App\Constants\Enums\Flow\Status;
use App\Models\Flow;
use other\Flow2\Contracts\FlowStateInterface;

final class ProcessState implements FlowStateInterface
{
    public function enter(Flow $flow): void {}
    public function toProcess(Flow $flow): void {}
    public function toSuccess(Flow $flow): void
    {
        $flow->status = Status::SUCCESS->value;
        $flow->save();
    }
    public function toReject(Flow $flow): void
    {
        $flow->status = Status::REJECT->value;
        $flow->save();
    }
    public function toCancel(Flow $flow): void
    {
        $flow->status = Status::CANCEL->value;
        $flow->save();
    }
}
