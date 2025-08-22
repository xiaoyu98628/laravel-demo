<?php

namespace App\Constants\Enums;

interface EnumInterface
{
    /**
     * 获取用户友好的标签
     * @return string
     */
    public function label(): string;

    /**
     * 获取所有值的数组
     * @return array
     */
    public static function values(): array;
}
