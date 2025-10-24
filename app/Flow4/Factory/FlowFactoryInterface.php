<?php

declare(strict_types=1);

namespace App\Flow4\Factory;

use App\Models\Flow;

/**
 * 审批流程工厂接口
 * 定义创建审批流程的标准接口
 */
interface FlowFactoryInterface
{
    /**
     * 创建审批流程
     */
    public function createFlow(array $data): Flow;

    /**
     * 获取支持的业务类型
     */
    public function getSupportedBusinessType(): string;
}
