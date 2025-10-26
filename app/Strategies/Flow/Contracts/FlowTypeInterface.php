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
     * 处理审批流数据
     * @param  array  $inputs
     * @return array
     */
    public function buildFlowData(array $inputs): array;
}
