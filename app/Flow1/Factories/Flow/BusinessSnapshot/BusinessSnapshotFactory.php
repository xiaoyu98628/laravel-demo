<?php

declare(strict_types=1);

namespace App\Flow1\Factories\Flow\BusinessSnapshot;

abstract class BusinessSnapshotFactory
{
    public function generate(array $inputs): array
    {
        return [];
    }
}
