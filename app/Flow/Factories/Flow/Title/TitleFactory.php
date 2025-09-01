<?php

declare(strict_types=1);

namespace App\Flow\Factories\Flow\Title;

abstract class TitleFactory
{
    public function generate(array $inputs): string
    {
        return '';
    }
}
