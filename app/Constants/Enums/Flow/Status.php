<?php

declare(strict_types=1);

namespace App\Constants\Enums\Flow;

use App\Constants\Enums\BaseEnumTrait;
use App\Constants\Enums\EnumInterface;

enum Status: string implements EnumInterface
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
