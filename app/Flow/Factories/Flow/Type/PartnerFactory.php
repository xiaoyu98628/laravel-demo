<?php

declare(strict_types=1);

namespace App\Flow\Factories\Flow\Type;

use App\Flow\Factories\Flow\BusinessSnapshot\PartnerBusinessSnapshotFactory;
use App\Flow\Factories\Flow\Title\PartnerNameFactory;
use App\Flow\Factories\Flow\TypeInterface;
use Illuminate\Support\Arr;

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

    public function generateFlow(array $inputs): array
    {
        return [
            'parent_id' => Arr::get($inputs, 'parent_id'),
            'title'     => $this->generateName($inputs),
        ];
    }
}
