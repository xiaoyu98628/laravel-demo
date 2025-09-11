<?php

declare(strict_types=1);

namespace App\Flow\Factories\Flow\Type;

use App\Constants\Enums\Flow\BusinessType;
use App\Constants\Enums\Flow\Status;
use App\Flow\Factories\Flow\BusinessSnapshot\PartnerBusinessSnapshotFactory;
use App\Flow\Factories\Flow\Title\PartnerTitleFactory;
use App\Flow\Factories\Flow\TypeInterface;
use Illuminate\Support\Arr;

class PartnerFactory extends TypeFactory implements TypeInterface
{
    /**
     * 获取审批标题
     * @param  array  $inputs
     * @return string
     */
    public function generateName(array $inputs): string
    {
        return (new PartnerTitleFactory)->generate($inputs, $this->template);
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

    /**
     * 生成审批流程数据
     * @param  array  $inputs
     * @return array
     */
    public function generateFlow(array $inputs): array
    {
        return [
            'parent_id'                   => Arr::get($inputs, 'parent_id'),
            'title'                       => $this->generateName($inputs),
            'business_type'               => BusinessType::PARTNER->value,
            'business_id'                 => Arr::get($inputs, 'business_id'),
            'business_snapshot'           => $this->generateBusinessSnapshot($inputs),
            'status'                      => Status::PROCESS->value,
            'flow_node_template_snapshot' => $this->template,
            'callback'                    => Arr::get($this->template, 'callback'),
            'applicant_type'              => Arr::get($inputs, 'applicant_type'),
            'applicant_id'                => Arr::get($inputs, 'applicant_id'),
            'flow_template_id'            => Arr::get($this->template, 'id'),
        ];
    }
}
