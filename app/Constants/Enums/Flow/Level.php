<?php

declare(strict_types=1);

namespace App\Constants\Enums\Flow;

use App\Constants\Enums\BaseEnumTrait;
use App\Constants\Enums\EnumInterface;

enum Level: string implements EnumInterface
{
    use BaseEnumTrait;

    case MAIN    = 'main';
    case SUBFLOW = 'subflow';

    /**
     * 获取用户友好的标签
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::MAIN    => '主流程',
            self::SUBFLOW => '子流程',
        };
    }
}
