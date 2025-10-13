<?php
declare(strict_types=1);

namespace App\Flow\Contracts;

use App\Models\Flow;

interface FlowStateInterface
{
    public function enter(Flow $flow): void;
    public function toProcess(Flow $flow): void;
    public function toSuccess(Flow $flow): void;
    public function toReject(Flow $flow): void;
    public function toCancel(Flow $flow): void;
}