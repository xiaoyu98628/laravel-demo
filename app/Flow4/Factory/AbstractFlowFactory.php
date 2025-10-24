<?php

declare(strict_types=1);

namespace App\Flow4\Factory;

use App\Constants\Enums\Flow\Level;
use App\Constants\Enums\Flow\Status;
use App\Models\Flow;
use App\Repositories\FlowRepositories;
use App\Repositories\FlowTemplateRepositories;

/**
 * 抽象审批流程工厂
 * 实现通用的流程创建逻辑
 */
abstract class AbstractFlowFactory implements FlowFactoryInterface
{
    protected FlowRepositories $flowRepository;

    protected FlowTemplateRepositories $templateRepository;

    public function __construct(
        FlowRepositories $flowRepository,
        FlowTemplateRepositories $templateRepository
    ) {
        $this->flowRepository     = $flowRepository;
        $this->templateRepository = $templateRepository;
    }

    /**
     * 模板方法 - 定义创建流程的标准步骤
     */
    public function createFlow(array $data): Flow
    {
        // 1. 验证数据
        $this->validateData($data);

        // 2. 获取流程模板
        $template = $this->getTemplate($data);

        // 3. 准备流程数据
        $flowData = $this->prepareFlowData($data, $template);

        // 4. 创建流程实例
        $flow = $this->flowRepository->store($flowData);

        // 5. 处理业务特定逻辑
        $this->processBusinessLogic($flow, $data);

        return $flow;
    }

    /**
     * 验证输入数据 - 子类可重写
     */
    protected function validateData(array $data): void
    {
        if (empty($data['title'])) {
            throw new \InvalidArgumentException('流程标题不能为空');
        }

        if (empty($data['business_id'])) {
            throw new \InvalidArgumentException('业务ID不能为空');
        }

        if (empty($data['applicant_id'])) {
            throw new \InvalidArgumentException('申请人ID不能为空');
        }
    }

    /**
     * 获取流程模板 - 子类可重写
     */
    protected function getTemplate(array $data)
    {
        $businessType = $this->getSupportedBusinessType();
        $template     = $this->templateRepository->query()
            ->where('type', $businessType)
            ->where('status', 'enable')
            ->with('nodeTemplate.children.conditionNode')
            ->first();

        if (! $template) {
            throw new \RuntimeException("未找到可用的{$businessType}流程模板");
        }

        return $template;
    }

    /**
     * 准备流程数据
     */
    protected function prepareFlowData(array $data, $template): array
    {
        return [
            'title'                       => $data['title'],
            'business_type'               => $this->getSupportedBusinessType(),
            'business_id'                 => $data['business_id'],
            'parent_flow_id'              => $data['parent_flow_id']    ?? null,
            'parent_node_id'              => $data['parent_node_id']    ?? null,
            'level'                       => $data['level']             ?? Level::MAIN->value,
            'business_snapshot'           => $data['business_snapshot'] ?? [],
            'status'                      => $data['is_draft']          ?? false ? Status::CREATE->value : Status::PROCESS->value,
            'flow_node_template_snapshot' => $template->nodeTemplate->toArray(),
            'callback'                    => $template->callback     ?? [],
            'applicant_type'              => $data['applicant_type'] ?? 'user',
            'applicant_id'                => $data['applicant_id'],
            'extend'                      => $data['extend'] ?? [],
            'flow_template_id'            => $template->id,
        ];
    }

    /**
     * 处理业务特定逻辑 - 子类必须实现
     */
    abstract protected function processBusinessLogic(Flow $flow, array $data): void;
}
