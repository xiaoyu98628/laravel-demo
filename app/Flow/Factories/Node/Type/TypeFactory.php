<?php

declare(strict_types=1);

namespace App\Flow\Factories\Node\Type;

use App\Constants\Enums\FlowNode\Status;
use App\Constants\Enums\FlowNode\Type;
use Illuminate\Support\Arr;

abstract class TypeFactory
{
    protected array $template;

    /**
     * 设置模版
     * @param  array  $template
     * @return $this
     */
    public function setTemplate(array $template): static
    {
        $this->template = $template;

        return $this;
    }

    /**
     * 生成节点数据
     * @param  array  $inputs
     * @return array
     */
    public function generateNode(array $inputs): array
    {
        return [
            'parent_id' => Arr::get($inputs, 'parent_id'),
            'depth'     => Arr::get($this->template, 'depth'),
            'name'      => Arr::get($this->template, 'name'),
            'type'      => $this->getType(),
            'rules'     => Arr::get($this->template, 'rules'),
            'status'    => Status::PROCESS->value,
            'callback'  => Arr::get($this->template, 'callback'),
            'flow_id'   => Arr::get($this->template, 'flow_id'),
        ];
    }
}
