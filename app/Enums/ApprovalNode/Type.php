<?php

namespace App\Enums\ApprovalNode;

use App\Enums\BaseEnumTrait;

enum Type: string
{
    use BaseEnumTrait;

    case CONDITION = 'condition';
    case APPROVAL = 'approval';
    case CC = 'cc';

    /**
     * 获取用户友好的标签
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::CONDITION => '条件节点',
            self::APPROVAL => '审核节点',
            self::CC => '抄送节点',
        };
    }
}
