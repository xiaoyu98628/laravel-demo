<?php

namespace App\Constants\Enums\FlowNode;

use App\Constants\Enums\BaseEnumTrait;
use App\Constants\Enums\EnumInterface;

enum Mode: string implements EnumInterface
{
    use BaseEnumTrait;

    case All = 'all';
    case Any = 'any';

    /**
     * 获取用户友好的标签
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::All => '会签',
            self::Any => '或签',
        };
    }
}
