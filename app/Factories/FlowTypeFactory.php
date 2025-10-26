<?php

declare(strict_types=1);

namespace App\Factories;

use App\Constants\Enums\Flow\BusinessType;

/**
 * 审批流类型工厂
 * Class FlowTypeFactory
 */
class FlowTypeFactory
{
    public function make(string $type): string
    {
        return match ($type) {
            BusinessType::PROJECT->value => '',
            default                      => '123',
        };
    }
}
