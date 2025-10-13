<?php

declare(strict_types=1);

namespace App\Flow1\Factories;

use App\Constants\Enums\Flow\BusinessType;
use App\Flow1\Factories\Flow\Type\PartnerFactory;
use App\Flow1\Factories\Flow\TypeInterface;

class FlowFactory
{
    /**
     * @param  string  $type
     * @return TypeInterface
     * @throws \Exception
     */
    public static function make(string $type): TypeInterface
    {
        return match ($type) {
            BusinessType::PARTNER->value => new PartnerFactory,
            default                      => throw new \Exception('未知的审批类型'),
        };
    }
}
