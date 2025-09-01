<?php

namespace App\Flow\Factories\Node\Type;

use App\Flow\Factories\Node\TypeInterface;

class ApprovalFactory extends TypeFactory implements TypeInterface
{
    public function generateNode(array $inputs): array
    {
        return [];
    }

}
