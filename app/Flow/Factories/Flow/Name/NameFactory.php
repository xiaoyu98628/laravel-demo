<?php

declare(strict_types=1);

namespace App\Flow\Factories\Flow\Name;

abstract class NameFactory
{
    public function generate(array $inputs): string
    {
        return '';
    }
}
