<?php

declare(strict_types=1);

namespace App\Strategies\Flow\Types;

use App\Constants\Enums\Flow\Type;

/**
 * 通用审批
 * Class GeneralFlowStrategy
 */
class GeneralFlowStrategy extends AbstractFlowTypeStrategy
{
    /**
     * @param  array  $inputs
     * @return string
     */
    public function getTitle(array $inputs): string
    {
        return $this->template->name;
    }

    /**
     * 获取类型
     * @return string
     */
    public static function getType(): string
    {
        return Type::GENERAL->value;
    }
}
