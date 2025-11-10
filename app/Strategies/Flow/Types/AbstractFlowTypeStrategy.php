<?php

declare(strict_types=1);

namespace App\Strategies\Flow\Types;

use App\Constants\Enums\Flow\Level;
use App\Constants\Enums\Flow\Status;
use App\Constants\Enums\FlowNode\Type;
use App\Models\FlowTemplate;
use App\Repositories\FlowTemplateRepositories;
use App\Strategies\Flow\Contracts\FlowTypeInterface;
use Illuminate\Support\Arr;
use InvalidArgumentException;

abstract class AbstractFlowTypeStrategy implements FlowTypeInterface
{
    protected FlowTemplate $template;

    protected array $inputs;

    public function __construct(
        private FlowTemplateRepositories $flowTemplateRepositories,
    ) {}

    /**
     * 统一初始化（方便链式使用）
     */
    public function initialize(FlowTemplate $template, array $inputs): static
    {
        return $this->setTemplate($template)->setInputs($inputs);
    }

    /**
     * 设置模板
     * @param  FlowTemplate  $template
     * @return static
     */
    public function setTemplate(FlowTemplate $template): static
    {
        $this->template = $template;

        return $this;
    }

    /**
     * 设置参数
     * @param  array  $inputs
     * @return $this
     */
    public function setInputs(array $inputs): static
    {
        $this->inputs = $inputs;

        return $this;
    }

    /**
     * 构建数据
     * @return array
     */
    public function build(): array
    {
        $this->ensureInitialized();
        $this->validateBusinessData();

        return $this->getData();
    }

    /**
     * 获取数据
     * @return array
     */
    public function getData(): array
    {
        return [
            'title'             => $this->getTitle(),
            'type'              => static::getType(),
            'code'              => Arr::get($this->inputs, 'code'),
            'parent_flow_id'    => Arr::get($this->inputs, 'parent_flow_id'),
            'parent_node_id'    => Arr::get($this->inputs, 'parent_node_id'),
            'level'             => Arr::get($this->inputs, 'level', Level::MAIN->value),
            'business_id'       => Arr::get($this->inputs, 'business_id'),
            'business_snapshot' => $this->getBusinessSnapshot(),
            'status'            => Status::CREATED->value,
            'template_snapshot' => $this->getTemplateSnapshot(),
            'callback'          => $this->template->callback,
            'applicant_type'    => Arr::get($this->inputs, 'applicant_type'),
            'applicant_id'      => Arr::get($this->inputs, 'applicant_id'),
            'extend'            => Arr::get($this->inputs, 'extend'),
            'flow_template_id'  => $this->template->id,
        ];
    }

    protected function getTemplateSnapshot(array $template = [], array $templateSnapshot = []): array
    {
        if (empty($template)) {
            $template = Arr::get($this->template->toArray(), 'node_template');
        }

        match (Arr::get($template, 'type')) {
            Type::CONDITION_ROUTE->value => value(function () use ($template, &$templateSnapshot) {
                foreach (Arr::get($template, 'condition_node') as $conditionNode) {
                    if (! empty(Arr::get($conditionNode, 'children', []))) {
                        $templateSnapshot = [...$templateSnapshot, ...$this->getTemplateSnapshot(Arr::get($conditionNode, 'children', []))];
                    }
                }
            }),
            default => '',
        };

        if (Arr::get($template, 'type') == Type::SUBFLOW->value) {

            $SubFlowTemplate = $this->flowTemplateRepositories->query()
                ->with([
                    'nodeTemplate' => fn ($query) => $query->whereNull('parent_id')->with(['children', 'conditionNode']),
                ])->where('status', \App\Constants\Enums\FlowTemplate\Status::ENABLE->value)
                ->where('id', Arr::get($template, 'rules.id'))
                ->orderBy('id', 'desc')
                ->firstOrFail();

            $templateSnapshot[] = [
                'id'       => Arr::get($template, 'rules.id'),
                'node_id'  => Arr::get($template, 'id'),
                'template' => $SubFlowTemplate->toArray(),
            ];
        }

        if (! empty(Arr::get($template, 'children', []))) {
            $templateSnapshot = [...$templateSnapshot, ...$this->getTemplateSnapshot(Arr::get($template, 'children', []))];
        }

        return $templateSnapshot;
    }

    /**
     * 验证业务数据
     * @return void
     */
    protected function validateBusinessData(): void {}

    /**
     * 子类可以覆写此方法来自定义业务快照
     */
    protected function getBusinessSnapshot(): array
    {
        return $this->inputs;
    }

    /**
     * 确保初始化完整
     */
    protected function ensureInitialized(): void
    {
        if (empty($this->template)) {
            throw new InvalidArgumentException('流程模版未初始化');
        }

        if (empty($this->inputs)) {
            throw new InvalidArgumentException('业务数据未初始化');
        }
    }

    /**
     * 获取类型
     * @return string
     */
    abstract public static function getType(): string;

    /**
     * 模式是否支持
     * @param  string  $type
     * @return bool
     */
    public function supports(string $type): bool
    {
        return $type === static::getType();
    }

    /**
     * 获取标题
     * @return string
     */
    protected function getTitle(): string
    {
        return $this->template->name;
    }
}
