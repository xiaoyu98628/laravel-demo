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
     * 验证业务数据
     * @return void
     */
    protected function validateBusinessData(): void {}

    /**
     * @return string
     */
    protected function getTitle(): string
    {
        return $this->template->name;
    }

    /**
     * 获取类型
     * @return string
     */
    protected static function getType(): string
    {
        return Type::GENERAL->value;
    }

    /**
     * 模式是否支持
     * @param  string  $type
     * @return bool
     */
    public function supports(string $type): bool
    {
        return $type === self::getType();
    }
}
