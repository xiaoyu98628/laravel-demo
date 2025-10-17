<?php

declare(strict_types=1);

namespace App\Constants\Enums\Flow;

use App\Constants\Enums\BaseEnumTrait;

enum Status: string
{
    use BaseEnumTrait;

    case CREATE  = 'create';
    case PROCESS = 'process';
    case WAITING = 'waiting';
    case SUCCESS = 'success';
    case REJECT  = 'reject';
    case CANCEL  = 'cancel';

    /**
     * 获取用户友好的标签
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::CREATE  => '创建',
            self::PROCESS => '进行中',
            self::WAITING => '等待',
            self::SUCCESS => '通过',
            self::REJECT  => '驳回',
            self::CANCEL  => '取消',
        };
    }
}
