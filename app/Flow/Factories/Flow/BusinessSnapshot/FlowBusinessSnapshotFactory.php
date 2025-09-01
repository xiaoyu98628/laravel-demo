<?php

declare(strict_types=1);

namespace App\Flow\Factories\Flow\BusinessSnapshot;

abstract class FlowBusinessSnapshotFactory
{
    public function generate(array $inputs): array
    {
        return [];
    }
}
