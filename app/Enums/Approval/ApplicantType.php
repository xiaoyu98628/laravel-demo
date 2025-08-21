<?php

namespace App\Enums\Approval;

use App\Enums\BaseEnumTrait;

enum ApplicantType: string
{
    use BaseEnumTrait;

    case USER = 'user';
    case ADMIN = 'admin';

    /**
     * 获取用户友好的标签
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::USER => '用户',
            self::ADMIN => '管理员',
        };
    }
}
