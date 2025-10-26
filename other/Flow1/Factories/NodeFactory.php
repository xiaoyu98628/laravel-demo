<?php

declare(strict_types=1);

namespace other\Flow1\Factories;

use App\Constants\Enums\FlowNode\Type;
use other\Flow1\Factories\Node\Type\ApprovalFactory;
use other\Flow1\Factories\Node\TypeInterface;

class NodeFactory
{
    /**
     * @param  string  $type
     * @return TypeInterface
     * @throws \Exception
     */
    public static function make(string $type): TypeInterface
    {
        return match ($type) {
            Type::START->value    => '',
            Type::APPROVAL->value => new ApprovalFactory,
            default               => throw new \Exception('未知的审批类型'),
        };
    }
}
