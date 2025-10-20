<?php

declare(strict_types=1);

namespace App\Constants\Enums\FlowNodeTemplate;

use App\Constants\Enums\BaseEnumTrait;
use App\Constants\Enums\EnumInterface;

enum Type: string implements EnumInterface
{
    use BaseEnumTrait;

    case START           = 'start';
    case APPROVAL        = 'approval';
    case CC              = 'cc';
    case CONDITION       = 'condition';
    case CONDITION_ROUTE = 'condition_route';
    case SUBFLOW         = 'subflow';
    case END             = 'end';

    /**
     * 获取用户友好的标签
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::START           => '开始节点',
            self::CONDITION       => '条件节点',
            self::CONDITION_ROUTE => '条件路由节点',
            self::APPROVAL        => '审核节点',
            self::CC              => '抄送节点',
            self::SUBFLOW         => '子流程节点',
            self::END             => '结束节点',
        };
    }
}
