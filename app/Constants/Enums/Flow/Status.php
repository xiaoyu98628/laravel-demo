<?php

declare(strict_types=1);

namespace App\Constants\Enums\Flow;

use App\Constants\Enums\BaseEnumTrait;
use App\Constants\Enums\EnumInterface;

enum Status: string implements EnumInterface
{
    use BaseEnumTrait;

    // 进行时状态（正在处理）
    case PROCESSING = 'processing';
    case WAITING    = 'waiting';

    // 过去式状态（已完成处理）
    case CREATED  = 'created';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case CANCELED = 'canceled';

    /**
     * 获取用户友好的标签
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::CREATED    => '已创建',
            self::PROCESSING => '进行中',
            self::WAITING    => '等待中',
            self::APPROVED   => '已通过',
            self::REJECTED   => '已驳回',
            self::CANCELED   => '已取消',
        };
    }
}
