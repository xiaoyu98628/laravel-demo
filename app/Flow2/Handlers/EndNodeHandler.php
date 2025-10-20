<?php
declare(strict_types=1);

namespace App\Flow2\Handlers;

use App\Constants\Enums\FlowNode\Type;
use App\Flow2\Contracts\NodeHandlerInterface;
use App\Models\Flow;
use App\Models\FlowNode;

final class EndNodeHandler implements NodeHandlerInterface
{
    public function supports(string $nodeType): bool
    {
        return $nodeType === Type::END->value;
    }

    public function handle(Flow $flow, FlowNode $node): void
    {
        $node->status = 'auto';
        $node->save();
        // 引擎将把流程置为 success
    }

}
