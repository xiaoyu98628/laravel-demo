<?php

declare(strict_types=1);

namespace App\Constants\Enums\FlowTemplate;

use App\Constants\Enums\BaseEnumTrait;
use App\Constants\Enums\EnumInterface;

enum Type: string implements EnumInterface
{
    use BaseEnumTrait;

    case GENERAL = 'general';

    /**
     * 获取用户友好的标签
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::GENERAL => '通用审批',
        };
    }
}
