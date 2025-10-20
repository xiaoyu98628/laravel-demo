<?php
declare(strict_types=1);

namespace App\Flow2\States;

use App\Constants\Enums\Flow\Status;
use App\Flow2\Contracts\FlowStateInterface;
use App\Models\Flow;

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
