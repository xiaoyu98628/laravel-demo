<?php

declare(strict_types=1);

namespace App\Flow\Factories\Flow\BusinessSnapshot;

use App\Flow\Factories\Flow\BusinessSnapshotInterface;

class PartnerBusinessSnapshotFactory extends BusinessSnapshotFactory implements BusinessSnapshotInterface
{
    public function generate(array $inputs): array
    {
        return [];
    }
}
