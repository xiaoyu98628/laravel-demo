<?php

namespace App\Constants\Enums\FlowNode;

use App\Constants\Enums\BaseEnumTrait;

enum Status: string
{
    use BaseEnumTrait;

    case PROCESS = 'process';
    case APPROVE = 'approve';
    case REJECT = 'reject';
    case SKIP = 'skip';
    case AUTO = 'auto';
    case CANCEL = 'cancel';

    /**
     * 获取用户友好的标签
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::PROCESS => '进行中',
            self::APPROVE => '通过',
            self::REJECT => '驳回',
            self::SKIP => '跳过',
            self::AUTO => '自动',
            self::CANCEL => '取消',
        };
    }
}
