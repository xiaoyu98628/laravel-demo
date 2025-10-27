<?php

declare(strict_types=1);

namespace App\Builders;

use App\Constants\Enums\FlowTemplate\Status;
use App\Factories\FlowTypeFactory;
use App\Models\Flow;
use App\Models\FlowTemplate;
use App\Repositories\FlowRepositories;
use App\Repositories\FlowTemplateRepositories;
use Illuminate\Support\Arr;

/**
 * 负责将 FlowTemplate 转为 Flow 实例
 * 如果 $inputs['status'] === 'create'（草稿），则只创建 flow 记录，不创建节点实例。
 * Class FlowBuilder
 */
final readonly class FlowBuilder
{
    private FlowTemplate $template;

    public function __construct(
        private FlowTemplateRepositories $flowTemplateRepositories,
        private FlowRepositories $flowRepositories,
    ) {}

    /**
     * 获取模板
     * @param  string  $code
     * @return void
     */
    public function getTemplate(string $code): void
    {
        $this->template = $this->flowTemplateRepositories->query()
            ->with([
                'nodeTemplate' => fn ($query) => $query->whereNull('parent_id')->with(['children', 'conditionNode']),
            ])->where('status', Status::ENABLE->value)
            ->where('code', $code)
            ->orderBy('id', 'desc')
            ->firstOrFail();
    }

    /**
     * @param  string  $code
     * @param  array  $inputs
     * @return Flow
     * @throws \Exception
     */
    public function build(string $code, array $inputs): Flow
    {
        $this->getTemplate($code);

        // 返回 Flow 实例
        $factory = FlowTypeFactory::make($this->template->type);

        $flowData = $factory->setInputs($inputs)->setTemplate($this->template)->build();

        dd($flowData);

        $flow = $this->flowRepositories->store($flowData);

        // 是草稿则不创建节点实例
        if (Arr::get($inputs, 'is_draft')) {
            return $flow;
        }

    }
}
