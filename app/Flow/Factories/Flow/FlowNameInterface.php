<?php

declare(strict_types=1);

namespace App\Flow\Factories\Flow;

/**
 * 审批流程名称接口
 */
interface FlowNameInterface
{
    public function generate(array $inputs): string;
}
