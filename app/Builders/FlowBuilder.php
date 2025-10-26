<?php

declare(strict_types=1);

namespace App\Builders;

/**
 * 负责将 FlowTemplate 转为 Flow 实例
 * 如果 $inputs['status'] === 'create'（草稿），则只创建 flow 记录，不创建节点实例。
 * Class FlowBuilder
 */
class FlowBuilder
{
    public function build() {}
}
