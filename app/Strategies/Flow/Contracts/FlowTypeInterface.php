<?php

declare(strict_types=1);

namespace App\Strategies\Flow\Contracts;

interface FlowTypeInterface
{
    /**
     * 是否支持
     * @param  string  $type
     * @return bool
     */
    public static function supports(string $type): bool;
}
