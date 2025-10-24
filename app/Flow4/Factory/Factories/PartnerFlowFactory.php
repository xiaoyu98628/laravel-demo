<?php

declare(strict_types=1);

namespace App\Flow4\Factory\Factories;

use App\Flow4\Factory\AbstractFlowFactory;
use App\Models\Flow;

/**
 * 合作者审批流程工厂
 * 应用场景：合作者认证相关审批
 */
class PartnerFlowFactory extends AbstractFlowFactory
{
    public function getSupportedBusinessType(): string
    {
        return 'partner';
    }

    protected function processBusinessLogic(Flow $flow, array $data): void
    {
        $extend = $flow->extend ?? [];
        $extend['partner_level'] = $data['partner_level'] ?? 'basic';
        $extend['verification_type'] = $data['verification_type'] ?? 'standard';

        $this->flowRepository->update($flow->id, ['extend' => $extend]);
    }
}
