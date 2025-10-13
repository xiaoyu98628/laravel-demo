<?php

declare(strict_types=1);

namespace App\Flow1\Factories\Flow\BusinessSnapshot;

use App\Flow1\Factories\Flow\BusinessSnapshotInterface;

class PartnerBusinessSnapshotFactory extends BusinessSnapshotFactory implements BusinessSnapshotInterface
{
    public function generate(array $inputs): array
    {
        return [];
    }
}
