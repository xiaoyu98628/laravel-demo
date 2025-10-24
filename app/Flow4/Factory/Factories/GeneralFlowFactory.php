<?php

declare(strict_types=1);

namespace App\Flow4\Factory\Factories;

use App\Flow4\Factory\AbstractFlowFactory;
use App\Models\Flow;

/**
 * 通用审批流程工厂
 * 应用场景：当没有匹配的特定审批类型时，使用通用审批
 * 设计模式：工厂方法模式
 * 作用：提供默认的审批流程创建逻辑
 */
class GeneralFlowFactory extends AbstractFlowFactory
{
    public function getSupportedBusinessType(): string
    {
        return 'workflow'; // 使用workflow作为通用审批类型
    }

    /**
     * 通用审批的模板获取逻辑
     * 如果没找到对应类型的模板，使用通用模板
     */
    protected function getTemplate(array $data)
    {
        // 先尝试获取指定类型的模板
        if (!empty($data['business_type'])) {
            $template = $this->templateRepository->query()
                ->where('type', $data['business_type'])
                ->where('status', 'enable')
                ->with('nodeTemplate.children.conditionNode')
                ->first();

            if ($template) {
                return $template;
            }
        }

        // 如果没找到，使用通用模板
        $template = $this->templateRepository->query()
            ->where('type', 'workflow')
            ->where('status', 'enable')
            ->with('nodeTemplate.children.conditionNode')
            ->first();

        if (!$template) {
            throw new \RuntimeException("未找到可用的通用审批流程模板");
        }

        return $template;
    }

    protected function processBusinessLogic(Flow $flow, array $data): void
    {
        // 通用审批的扩展信息
        $extend = $flow->extend ?? [];
        $extend['flow_type'] = 'general';
        $extend['custom_fields'] = $data['custom_fields'] ?? [];

        $this->flowRepository->update($flow->id, ['extend' => $extend]);
    }
}
