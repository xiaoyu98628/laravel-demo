<?php

declare(strict_types=1);

namespace App\Constants\Enums\Flow;

use App\Constants\Enums\BaseEnumTrait;
use App\Constants\Enums\EnumInterface;

enum ApplicantType: string implements EnumInterface
{
    use BaseEnumTrait;

    case USER  = 'user';
    case ADMIN = 'admin';

    /**
     * 获取用户友好的标签
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::USER  => '用户',
            self::ADMIN => '管理员',
        };
    }
}
