<?php

namespace App\Enums\ApprovalTemplate;

use App\Enums\BaseEnumTrait;

enum Status: string
{
    use BaseEnumTrait;

    case ENABLE = 'enable';
    case DISABLE = 'disable';

    /**
     * 获取用户友好的标签
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::ENABLE => '启用',
            self::DISABLE => '禁用',
        };
    }
}
