<?php

declare(strict_types=1);

namespace App\Flow\Strategies\Flow\Types;

use App\Constants\Enums\Flow\Status as FlowStatus;
use App\Constants\Enums\FlowTemplate\Status as FlowTemplateStatus;
use App\Models\FlowNodeTemplate;
use App\Models\FlowTemplate;
use App\Repositories\FlowTemplateRepositories;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use RuntimeException;

abstract class TypeStrategy
{
    protected ?FlowTemplate $template;

    protected ?FlowNodeTemplate $nodeTemplate;

    public function __construct(
        private readonly FlowTemplateRepositories $flowTemplateRepositories
    ) {}

    /**
     * 选择模板
     * @param  array  $inputs
     * @return FlowTemplate
     */
    public function flowTemplate(array $inputs): FlowTemplate
    {
        $this->template = $this->flowTemplateRepositories->query()
            ->where('type', static::$type)
            ->where('status', FlowTemplateStatus::ENABLE->value)
            ->first();

        if (empty($this->template)) {
            throw new ModelNotFoundException('未找到启用的财务审批模板');
        }

        return $this->template;
    }

    /**
     * 构建流程节点模板数据
     * @param  array  $inputs
     * @return FlowNodeTemplate
     */
    public function flowNodeTemplate(array $inputs): FlowNodeTemplate
    {
        if (! empty($this->nodeTemplate)) {
            return $this->nodeTemplate;
        }

        if (empty($this->template)) {
            throw new RuntimeException('审批模板未选择');
        }

        // 读取模板节点树
        $this->nodeTemplate = $this->template->nodeTemplate()->with(['children', 'conditionNode'])->first();

        if (empty($this->nodeTemplate)) {
            throw new RuntimeException('模板未配置节点');
        }

        return $this->nodeTemplate;
    }

    /**
     * 获取流程状态
     * @param  array  $inputs
     * @return string
     */
    public function getStatus(array $inputs): string
    {
        return Arr::get($inputs, 'is_draft', false)
            ? FlowStatus::PROCESS->value
            : FlowStatus::CREATE->value;
    }
}
