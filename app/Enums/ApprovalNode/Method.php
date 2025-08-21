<?php

namespace App\Enums\ApprovalNode;

use App\Enums\BaseEnumTrait;

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
