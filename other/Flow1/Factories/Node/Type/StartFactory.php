<?php

declare(strict_types=1);

namespace other\Flow1\Factories\Node\Type;

use App\Constants\Enums\FlowNode\Type;
use other\Flow1\Factories\Node\TypeInterface;

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
