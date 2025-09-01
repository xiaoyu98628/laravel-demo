<?php

declare(strict_types=1);

namespace App\Flow\Factories\Flow;

/**
 * 业务数据快照接口
 */
interface BusinessSnapshotInterface
{
    public function generate(array $inputs): array;
}
