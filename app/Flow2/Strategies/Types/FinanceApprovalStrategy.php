<?php

declare(strict_types=1);

namespace App\Flow2\Strategies\Types;

use App\Flow2\Contracts\ApprovalTypeStrategyInterface;
use App\Models\FlowTemplate;
use App\Repositories\FlowTemplateRepositories;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class FinanceApprovalStrategy implements ApprovalTypeStrategyInterface
{
    public function __construct(private readonly FlowTemplateRepositories $templates) {}

    public function selectTemplate(array $inputs): FlowTemplate
    {
        $tpl = $this->templates->query()
            ->where('type', 'finance')
            ->where('status', 'enable')
            ->first();
        if (! $tpl) {
            throw new ModelNotFoundException('未找到启用的财务审批模板');
        }

        return $tpl;
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
