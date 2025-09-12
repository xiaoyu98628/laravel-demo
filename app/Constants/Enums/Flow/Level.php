<?php

declare(strict_types=1);

namespace App\Constants\Enums\Flow;

use App\Constants\Enums\BaseEnumTrait;

enum Level: string
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
