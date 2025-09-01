<?php

declare(strict_types=1);

namespace App\Flow\Factories\Flow\Type;

use App\Flow\Factories\Flow\TypeInterface;

class PartnerFactory extends TypeFactory implements TypeInterface
{
    public function generateName(array $inputs): string {}

    public function generateBusinessSnapshot(array $inputs): array {}
}
