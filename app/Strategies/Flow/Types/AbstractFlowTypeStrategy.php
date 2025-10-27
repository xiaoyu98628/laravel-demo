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

    protected array $inputs;

    /**
     * 设置模板
     * @param  FlowTemplate  $template
     * @return static
     */
    public function setTemplate(FlowTemplate $template): static
    {
        $this->template = $template;

        return $this;
    }

    /**
     * 设置参数
     * @param  array  $inputs
     * @return $this
     */
    public function setInputs(array $inputs): static
    {
        $this->inputs = $inputs;

        return $this;
    }

    /**
     * 构建数据
     * @return array
     */
    public function build(): array
    {
        // 验证业务数据
        $this->validateBusinessData();

        return $this->getData();
    }

    /**
     * 获取数据
     * @return array
     */
    public function getData(): array
    {
        return [
            'title'             => $this->getTitle(),
            'type'              => static::getType(),
            'code'              => Arr::get($this->inputs, 'code'),
            'parent_flow_id'    => Arr::get($this->inputs, 'parent_flow_id'),
            'parent_node_id'    => Arr::get($this->inputs, 'parent_node_id'),
            'level'             => Arr::get($this->inputs, 'level', Level::MAIN->value),
            'business_id'       => Arr::get($this->inputs, 'business_id'),
            'business_snapshot' => $this->inputs,
            'status'            => Status::CREATED->value,
            'template_snapshot' => $this->getTemplateSnapshot(),
            'callback'          => $this->template->callback,
            'applicant_type'    => Arr::get($this->inputs, 'applicant_type'),
            'applicant_id'      => Arr::get($this->inputs, 'applicant_id'),
            'extend'            => Arr::get($this->inputs, 'extend'),
            'flow_template_id'  => $this->template->id,
        ];
    }

    protected function getTemplateSnapshot(array $snapshot = []): array
    {
        $template = $this->template->toArray();

        return [
            Level::MAIN->value => [
                'id'       => $template['id'],
                'template' => $template,
            ],
            Level::SUBFLOW->value => [],
        ];
    }

    /**
     * 验证业务数据
     * @return void
     */
    abstract protected function validateBusinessData(): void;

    /**
     * 获取类型
     * @return string
     */
    abstract protected static function getType(): string;

    /**
     * 获取标题
     * @return string
     */
    abstract protected function getTitle(): string;
}
