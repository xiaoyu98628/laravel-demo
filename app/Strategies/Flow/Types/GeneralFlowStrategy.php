<?php

declare(strict_types=1);

namespace App\Strategies\Flow\Types;

use App\Constants\Enums\Flow\Type;

/**
 * 通用审批
 * Class GeneralFlowStrategy
 */
class GeneralFlowStrategy extends AbstractFlowTypeStrategy
{
    /**
     * 是否支持
     * @param  string  $type
     * @return bool
     */
    public static function supports(string $type): bool
    {
        return $type == Type::GENERAL->value;
    }
}
