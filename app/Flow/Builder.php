<?php

declare(strict_types=1);

namespace App\Flow;

use App\Constants\Enums\FlowTemplate\Status;
use App\Flow\Factories\FlowFactory;
use App\Flow\Factories\NodeFactory;
use App\Repositories\FlowNodeRepositories;
use App\Repositories\FlowRepositories;
use App\Repositories\FlowTemplateRepositories;

/**
 * 负责创建审批流程
 */
final class Builder
{
    public function __construct(
        private readonly FlowTemplateRepositories $flowTemplateRepositories,
        private readonly FlowRepositories $flowRepositories,
        private readonly FlowNodeRepositories $flowNodeRepositories,
    ) {}

    /** @var string 审批流类型 */
    private string $type;

    /** @var array 业务参数 */
    private array $inputs;

    /** @var array 审批模版 */
    private array $template;

    /**
     * @param  string  $type
     * @return $this
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @param  array  $inputs
     * @return $this
     */
    public function setInputs(array $inputs): self
    {
        $this->inputs = $inputs;

        return $this;
    }

    /**
     * 获取审批模版
     * @return $this
     */
    public function getTemplate(): self
    {
        $this->template = $this->flowTemplateRepositories->query()
            ->where('type', $this->type)
            ->where('status', Status::ENABLE->value)
            ->with([
                'nodeTemplate' => fn ($query) => $query->whereNull('parent_id')->with('children'),
            ])->first()->toArray();

        return $this;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function flow(): self
    {
        $flow = FlowFactory::make($this->type)->setTemplate($this->template)->generateFlow($this->inputs);
        $this->flowRepositories->store($flow);

        return $this;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function node(): self
    {
        $node = NodeFactory::make($this->type)->setTemplate($this->template)->generateNode($this->inputs);
        $this->flowNodeRepositories->store($node);

        return $this;
    }

    public function task(): self
    {
        return $this;
    }
}
