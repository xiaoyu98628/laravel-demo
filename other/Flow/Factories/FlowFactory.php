<?php

declare(strict_types=1);

namespace other\Flow\Factories;

use App\Constants\Enums\Flow\BusinessType;

/**
 * FlowFactory 负责动态获取流程对象
 */
class FlowFactory
{
    public function make(string $type)
    {
        return match ($type) {
            BusinessType::PROJECT->value => '',
            default                      => throw new \InvalidArgumentException('Invalid flow type'),
        };
    }
}
