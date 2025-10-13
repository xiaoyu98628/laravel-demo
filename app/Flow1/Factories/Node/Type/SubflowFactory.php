<?php

declare(strict_types=1);

namespace App\Flow1\Factories\Node\Type;

use App\Constants\Enums\FlowNode\Type;
use App\Flow1\Factories\Node\TypeInterface;

class SubflowFactory extends TypeFactory implements TypeInterface
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return Type::SUBFLOW->value;
    }
}
