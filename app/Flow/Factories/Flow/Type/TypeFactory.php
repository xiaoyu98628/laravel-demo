<?php

declare(strict_types=1);

namespace App\Flow\Factories\Flow\Type;

abstract class TypeFactory
{
    abstract public function generateName(array $inputs): string;

    abstract public function generateBusinessSnapshot(array $inputs): array;
}
