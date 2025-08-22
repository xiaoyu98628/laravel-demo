<?php

namespace App\Constants\Enums\Approval;

use App\Constants\Enums\BaseEnumTrait;

enum BusinessType: string
{
    use BaseEnumTrait;

    case ORDER = 'order';

    /**
     * 获取用户友好的标签
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::ORDER => '订单',
        };
    }
}
