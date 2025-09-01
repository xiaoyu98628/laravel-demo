<?php

declare(strict_types=1);

namespace App\Flow\Factories\Flow\Type;

use App\Flow\Factories\Flow\BusinessSnapshot\PartnerBusinessSnapshotFactory;
use App\Flow\Factories\Flow\Name\PartnerNameFactory;
use App\Flow\Factories\Flow\TypeInterface;

class PartnerFactory implements TypeInterface
{
    /**
     * 获取审批标题
     * @param  array  $inputs
     * @return string
     */
    public function generateName(array $inputs): string
    {
        return (new PartnerNameFactory)->generate($inputs);
    }

    /**
     * 获取审批业务数据快照
     * @param  array  $inputs
     * @return array
     */
    public function generateBusinessSnapshot(array $inputs): array
    {
        return (new PartnerBusinessSnapshotFactory)->generate($inputs);
    }
}
