<?php

namespace App\Constants\Enums\Approval;

use App\Constants\Enums\BaseEnumTrait;

enum Status: string
{
    use BaseEnumTrait;

    case CREATE = 'create';
    case PROCESS = 'process';
    case SUCCESS = 'success';
    case REJECT = 'reject';
    case CANCEL = 'cancel';

    /**
     * 获取用户友好的标签
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::CREATE => '创建',
            self::PROCESS => '进行中',
            self::SUCCESS => '通过',
            self::REJECT => '驳回',
            self::CANCEL => '取消',
        };
    }
}
