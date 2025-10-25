<?php

declare(strict_types=1);

namespace App\Constants\Enums\FlowNodeTask;

use App\Constants\Enums\BaseEnumTrait;
use App\Constants\Enums\EnumInterface;

enum Status: string implements EnumInterface
{
    use BaseEnumTrait;

    // 进行时状态（正在处理）
    case PENDING = 'pending';

    // 过去式状态（已完成处理）
    case APPROVED    = 'approved';
    case REJECTED    = 'rejected';
    case TRANSFERRED = 'transferred';
    case FORWARDED   = 'forwarded';
    case ADD_SIGNED  = 'add_signed';
    case SKIPPED     = 'skipped';
    case AUTO        = 'auto';
    case CANCELED    = 'canceled';

    /**
     * 获取用户友好的标签
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::PENDING     => '待处理',
            self::APPROVED    => '已同意',
            self::REJECTED    => '已拒绝',
            self::TRANSFERRED => '已转让',
            self::FORWARDED   => '已转交',
            self::ADD_SIGNED  => '已加签',
            self::SKIPPED     => '已跳过',
            self::AUTO        => '自动',
            self::CANCELED    => '已取消',
        };
    }
}
