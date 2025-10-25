<?php

declare(strict_types=1);

namespace App\Constants\Enums\FlowNode;

use App\Constants\Enums\BaseEnumTrait;
use App\Constants\Enums\EnumInterface;

enum Status: string implements EnumInterface
{
    use BaseEnumTrait;

    // 进行时状态（正在处理）
    case PROCESSING = 'processing';

    // 过去式状态（已完成处理）
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case SKIPPED  = 'skipped';
    case AUTO     = 'auto';
    case CANCELED = 'canceled';

    /**
     * 获取用户友好的标签
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::PROCESSING => '进行中',
            self::APPROVED   => '已通过',
            self::REJECTED   => '已驳回',
            self::SKIPPED    => '已跳过',
            self::AUTO       => '自动处理',
            self::CANCELED   => '已取消',
        };
    }
}
