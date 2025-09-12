<?php

declare(strict_types=1);

namespace App\Flow\Factories\Node\Type;

use App\Constants\Enums\FlowNode\Type;
use App\Flow\Factories\Node\TypeInterface;

class StartFactory extends TypeFactory implements TypeInterface
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return Type::START->value;
    }
}
