<?php

declare(strict_types=1);

namespace App\Flow\Factories;

use App\Constants\Enums\Flow\BusinessType;
use App\Flow\Factories\Flow\Type\PartnerFactory;
use App\Flow\Factories\Flow\TypeInterface;

class NodeFactory
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
