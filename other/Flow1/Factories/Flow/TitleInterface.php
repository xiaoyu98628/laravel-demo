<?php

declare(strict_types=1);

namespace other\Flow1\Factories\Flow;

/**
 * 审批流程名称接口
 */
interface TitleInterface
{
    public function generate(array $inputs, array $template): string;
}
