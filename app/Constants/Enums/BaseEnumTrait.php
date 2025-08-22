<?php

namespace App\Constants\Enums;

trait BaseEnumTrait
{
    /**
     * 获取所有值的数组
     * @return array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
