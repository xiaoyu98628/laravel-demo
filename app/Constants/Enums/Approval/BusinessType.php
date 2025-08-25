<?php

namespace App\Constants\Enums\Approval;

use App\Constants\Enums\BaseEnumTrait;

enum BusinessType: string
{
    use BaseEnumTrait;

    case PARTNER   = 'partner';
    case PUBLISHER = 'publisher';
    case FINANCE   = 'finance';
    case EXECUTION = 'execution';
    case WORKFLOW  = 'workflow';
    case PROJECT   = 'project';

    /**
     * 获取用户友好的标签
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::PARTNER   => '合作者职业认证',
            self::PUBLISHER => '发布者认证',
            self::FINANCE   => '财务审批流',
            self::EXECUTION => '执行流',
            self::WORKFLOW  => '工作流',
            self::PROJECT   => '项目审批流',
        };
    }

    /**
     * @return string
     */
    public static function pattern(): string
    {
        return implode('|', self::values());
    }
}
