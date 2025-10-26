<?php

declare(strict_types=1);

namespace other\Flow4\Factory\Factories;

use App\Models\Flow;
use other\Flow4\Factory\AbstractFlowFactory;

/**
 * 项目审批流程工厂
 * 应用场景：项目立项、变更、结项等审批
 * 设计模式：工厂方法模式
 * 作用：封装项目审批流程的创建逻辑
 */
class ProjectFlowFactory extends AbstractFlowFactory
{
    public function getSupportedBusinessType(): string
    {
        return 'project';
    }

    protected function validateData(array $data): void
    {
        parent::validateData($data);

        if (empty($data['project_type'])) {
            throw new \InvalidArgumentException('项目类型不能为空');
        }
    }

    protected function processBusinessLogic(Flow $flow, array $data): void
    {
        $extend = $flow->extend ?? [];
        $extend['project_type'] = $data['project_type'];
        $extend['priority'] = $data['priority'] ?? 'normal';
        $extend['estimated_budget'] = $data['estimated_budget'] ?? 0;

        $this->flowRepository->update($flow->id, ['extend' => $extend]);
    }
}
