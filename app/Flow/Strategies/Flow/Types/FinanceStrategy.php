<?php

declare(strict_types=1);

namespace App\Flow\Strategies\Flow\Types;

use App\Constants\Enums\Flow\BusinessType;
use App\Constants\Enums\Flow\Level;
use App\Constants\Enums\Flow\Status;
use App\Flow\Contracts\FlowTypeStrategyInterface;
use Illuminate\Support\Arr;

final class FinanceStrategy extends TypeStrategy implements FlowTypeStrategyInterface
{
    public static string $type = BusinessType::FINANCE->value;

    /**
     * 构建标题
     * @param  array  $inputs
     * @return string
     */
    public function makeTitle(array $inputs): string
    {
        return 'xxx 发起的'.Arr::get($this->template, 'name');
    }

    /**
     * 构建业务数据
     * @param  array  $inputs
     * @return array
     */
    public function buildBusinessSnapshot(array $inputs): array
    {
        return $inputs;
    }

    /**
     * 构建流程数据
     * @param  array  $inputs
     * @return array
     */
    public function buildFlow(array $inputs): array
    {
        return [
            'title'                       => $this->makeTitle($inputs),
            'business_type'               => BusinessType::FINANCE->value,
            'business_id'                 => Arr::get($inputs, 'business_id'),
            'parent_flow_id'              => Arr::get($inputs, 'parent_flow_id'),
            'parent_node_id'              => Arr::get($inputs, 'parent_node_id'),
            'level'                       => Arr::get($inputs, 'level', Level::MAIN->value),
            'business_snapshot'           => $this->buildBusinessSnapshot($inputs),
            'status'                      => $this->getStatus($inputs),
            'flow_node_template_snapshot' => $this->flowNodeTemplate($inputs)->toArray(),
            'callback'                    => Arr::get($this->template, 'callback'),
            'applicant_type'              => Arr::get($inputs, 'applicant_type'),
            'applicant_id'                => Arr::get($inputs, 'applicant_id'),
            'flow_template_id'            => Arr::get($this->template, 'id'),
        ];
    }
}
