<?php

declare(strict_types=1);

namespace other\Flow1\Factories\Flow\BusinessSnapshot;

abstract class BusinessSnapshotFactory
{
    public function generate(array $inputs): array
    {
        return [];
    }
}
