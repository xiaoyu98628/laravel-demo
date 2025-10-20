<?php

declare(strict_types=1);

namespace App\Flow\Strategies\Flow\Types;

use App\Constants\Enums\FlowTemplate\Type;
use App\Flow\Contracts\FlowTypeStrategyInterface;

final class FinanceStrategy extends TypeStrategy implements FlowTypeStrategyInterface
{
    public static string $type = Type::FINANCE->value;

    public function buildFlow(array $inputs): array
    {
        return [];
    }

    public function buildBusinessSnapshot(array $inputs): array
    {
        // 示例：金额、科目、发票信息
        return [
            'amount'     => $inputs['amount']     ?? 0,
            'subject'    => $inputs['subject']    ?? '',
            'invoice_no' => $inputs['invoice_no'] ?? '',
        ];
    }

    public function makeTitle(array $inputs): string
    {
        return '财务报销：'.($inputs['subject'] ?? '未知科目');
    }

    public function enrichNodeRules(array $nodeTemplateTree, array $inputs): array
    {
        // 例如按金额动态设置会签/并签或审批人
        // Demo 保持原样
        return $nodeTemplateTree;
    }
}
