<?php

declare(strict_types=1);

namespace other\Flow4\Factory\Factories;

use App\Models\Flow;
use other\Flow4\Factory\AbstractFlowFactory;

/**
 * 财务审批流程工厂
 * 应用场景：财务相关的审批流程，如报销、付款等
 * 设计模式：工厂方法模式
 * 作用：封装财务审批流程的创建逻辑，支持特定的业务规则
 */
class FinanceFlowFactory extends AbstractFlowFactory
{
    public function getSupportedBusinessType(): string
    {
        return 'finance';
    }

    /**
     * 财务审批特定的数据验证
     */
    protected function validateData(array $data): void
    {
        parent::validateData($data);

        // 财务审批特定验证
        if (isset($data['amount']) && $data['amount'] <= 0) {
            throw new \InvalidArgumentException('审批金额必须大于0');
        }
    }

    /**
     * 处理财务审批特定逻辑
     */
    protected function processBusinessLogic(Flow $flow, array $data): void
    {
        // 财务审批特定处理逻辑
        $extend                 = $flow->extend                         ?? [];
        $extend['finance_type'] = $data['finance_type'] ?? 'general';
        $extend['amount']       = $data['amount']             ?? 0;
        $extend['department']   = $data['department']     ?? '';

        $this->flowRepository->update($flow->id, ['extend' => $extend]);
    }
}
