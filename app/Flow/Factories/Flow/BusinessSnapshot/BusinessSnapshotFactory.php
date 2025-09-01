<?php

declare(strict_types=1);

namespace App\Flow\Factories\Flow\BusinessSnapshot;

abstract class BusinessSnapshotFactory
{
    public function generate(array $inputs): array
    {
        return [];
    }
}
