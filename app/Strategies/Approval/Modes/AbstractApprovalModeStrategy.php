<?php

declare(strict_types=1);

namespace App\Strategies\Approval\Modes;

use App\Models\FlowNode;
use App\Strategies\Approval\Contracts\ApprovalModeInterface;

abstract class AbstractApprovalModeStrategy implements ApprovalModeInterface
{
    public function createTasks(FlowNode $node): void {}
}
