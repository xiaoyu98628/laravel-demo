<?php

declare(strict_types=1);

namespace App\Constants\Enums\FlowNodeTemplate;

use App\Constants\Enums\BaseEnumTrait;

enum Type: string
{
    use BaseEnumTrait;

    case CONDITION = 'condition';
    case APPROVAL  = 'approval';
    case CC        = 'cc';
    case SUBFLOW   = 'subflow';

    /**
     * 获取用户友好的标签
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::CONDITION => '条件节点',
            self::APPROVAL  => '审核节点',
            self::CC        => '抄送节点',
            self::SUBFLOW   => '子流程节点',
        };
    }
}
