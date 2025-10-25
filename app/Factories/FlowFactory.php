<?php

declare(strict_types=1);

namespace App\Factories;

use App\Constants\Enums\Flow\BusinessType;

/**
 * FlowFactory 负责动态获取流程对象
 */
class FlowFactory
{
    public function make(string $type): string
    {
        return match ($type) {
            BusinessType::PROJECT->value => '',
            default                      => '123',
        };
    }
}
