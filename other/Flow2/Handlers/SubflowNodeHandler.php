<?php

declare(strict_types=1);

namespace other\Flow2\Handlers;

use App\Constants\Enums\Flow\Level;
use App\Constants\Enums\Flow\Status;
use App\Constants\Enums\FlowNode\Type;
use App\Models\Flow;
use App\Models\FlowNode;
use App\Repositories\FlowRepositories;
use Illuminate\Support\Arr;
use other\Flow2\Contracts\NodeHandlerInterface;

final class SubflowNodeHandler implements NodeHandlerInterface
{
    public function __construct(private readonly FlowRepositories $flowRepo) {}

    public function supports(string $nodeType): bool
    {
        return $nodeType === Type::SUBFLOW->value;
    }

    public function handle(Flow $flow, FlowNode $node): void
    {
        // 创建子流程实例（使用 flow 表）
        $rules             = $node->rules ?? [];
        $subflowTemplateId = Arr::get($rules, 'subflow_template_id');
        $subTitle          = '子流程 - '.($flow->title ?? '');
        $sub               = $this->flowRepo->store([
            'title'                       => $subTitle,
            'business_type'               => $flow->business_type,
            'business_id'                 => $flow->business_id,
            'parent_flow_id'              => $flow->id,
            'parent_node_id'              => $node->id,
            'level'                       => Level::SUBFLOW->value,
            'business_snapshot'           => $flow->business_snapshot,
            'status'                      => Status::CREATE->value,
            'flow_node_template_snapshot' => null,
            'callback'                    => null,
            'applicant_type'              => $flow->applicant_type,
            'applicant_id'                => $flow->applicant_id,
            'extend'                      => null,
            'flow_template_id'            => $subflowTemplateId,
        ]);

        // 子流程创建后，当前节点置为 process 等待子流程完成
        $node->status = 'process';
        $node->extend = ['subflow_id' => $sub->id];
        $node->save();
    }
}
