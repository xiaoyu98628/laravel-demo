<?php

declare(strict_types=1);

namespace App\Strategies\Flow\Contracts;

use App\Models\FlowTemplate;

interface FlowTypeInterface
{
    /**
     * 初始化策略（链式调用）
     * @param  FlowTemplate  $template
     * @param  array  $inputs
     * @return $this
     */
    public function initialize(FlowTemplate $template, array $inputs): static;

    /**
     * 设置模板
     * @param  FlowTemplate  $template
     * @return static
     */
    public function setTemplate(FlowTemplate $template): static;

    /**
     * 设置参数
     * @param  array  $inputs
     * @return static
     */
    public function setInputs(array $inputs): static;

    /**
     * 构建流程数据
     * @return array
     */
    public function build(): array;

    /**
     * 模式是否支持
     * @param  string  $type
     * @return bool
     */
    public function supports(string $type): bool;

    /**
     * 获取策略支持的流程类型
     */
    public static function getType(): string;
}
