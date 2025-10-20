<?php

declare(strict_types=1);

namespace App\Constants\Enums\FlowTemplate;

use App\Constants\Enums\BaseEnumTrait;
use App\Constants\Enums\EnumInterface;

enum Status: string implements EnumInterface
{
    use BaseEnumTrait;

    case ENABLE  = 'enable';
    case DISABLE = 'disable';

    /**
     * 获取用户友好的标签
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::ENABLE  => '启用',
            self::DISABLE => '禁用',
        };
    }
}
