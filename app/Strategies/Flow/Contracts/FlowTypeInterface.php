<?php

declare(strict_types=1);

namespace App\Strategies\Flow\Contracts;

use App\Models\FlowTemplate;

interface FlowTypeInterface
{
    /**
     * 设置模板
     * @param  FlowTemplate  $template
     * @return self
     */
    public function setTemplate(FlowTemplate $template): self;

    /**
     * 设置参数
     * @param  array  $inputs
     * @return self
     */
    public function setInputs(array $inputs): self;

    /**
     * 处理审批流数据
     * @return array
     */
    public function build(): array;

    /**
     * 模式是否支持
     * @param  string  $type
     * @return bool
     */
    public function supports(string $type): bool;
}
