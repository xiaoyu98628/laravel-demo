<?php

namespace App\Constants\Enums;

use Illuminate\Support\Arr;

trait BaseEnumTrait
{
    /**
     * 获取所有值的数组
     * @return array
     */
    public static function values(): array
    {
        return Arr::pluck(self::cases(), 'value');
    }
}
