<?php

declare(strict_types=1);

namespace other\Flow1\Factories\Flow\BusinessSnapshot;

use other\Flow1\Factories\Flow\BusinessSnapshotInterface;

class PartnerBusinessSnapshotFactory extends BusinessSnapshotFactory implements BusinessSnapshotInterface
{
    public function generate(array $inputs): array
    {
        return [];
    }
}
