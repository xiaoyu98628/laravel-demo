<?php

namespace App\Constants\Enums\FlowNode;

use App\Constants\Enums\BaseEnumTrait;

enum Method: string
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
