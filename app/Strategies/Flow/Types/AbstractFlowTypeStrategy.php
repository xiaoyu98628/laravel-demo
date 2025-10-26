<?php

declare(strict_types=1);

namespace App\Strategies\Flow\Types;

use App\Constants\Enums\Flow\Level;
use App\Constants\Enums\Flow\Status;
use App\Models\FlowTemplate;
use App\Strategies\Flow\Contracts\FlowTypeInterface;
use Illuminate\Support\Arr;

abstract class AbstractFlowTypeStrategy implements FlowTypeInterface
{
    protected FlowTemplate $template;

    /**
     * 设置模板
     * @param FlowTemplate $template
     * @return self
     */
    public function setTemplate(FlowTemplate $template): self
    {
        $this->template = $template;

        return $this;
    }

    public function buildFlowData(array $inputs): array
    {
        return [
            'title'                       => $this->getTitle($inputs),
            'type'                        => static::getType(),
            'code'                        => Arr::get($inputs, 'code'),
            'parent_flow_id'              => Arr::get($inputs, 'parent_flow_id'),
            'parent_node_id'              => Arr::get($inputs, 'parent_node_id'),
            'level'                       => Arr::get($inputs, 'level', Level::MAIN->value),
            'business_id'                 => Arr::get($inputs, 'business_id'),
            'business_snapshot'           => $this->buildBusinessData($inputs),
            'status'                      => Status::CREATED->value,
            'flow_node_template_snapshot' => $this->template->toArray(),
            'callback'                    => $this->template->callback,
            'applicant_type'              => Arr::get($inputs, 'applicant_type'),
            'applicant_id'                => Arr::get($inputs, 'applicant_id'),
            'extend'                      => Arr::get($inputs, 'extend'),
            'flow_template_id'            => $this->template->id,
        ];
    }

    /**
     * 验证业务数据
     * @param  array  $inputs
     * @return array
     */
    public function buildBusinessData(array $inputs): array
    {
        return $inputs;
    }

    /**
     * 获取类型
     * @return string
     */
    abstract public static function getType(): string;

    /**
     * 获取标题
     * @param  array  $inputs
     * @return string
     */
    abstract public function getTitle(array $inputs): string;
}
