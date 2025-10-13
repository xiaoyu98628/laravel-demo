<?php

declare(strict_types=1);

namespace App\Flow;

use App\Constants\Enums\Flow\Level;
use App\Constants\Enums\Flow\Status;
use App\Constants\Enums\FlowNodeTask\Status as FlowNodeTaskStatus;
use App\Flow\Engine\FlowEngine;
use App\Flow\Factory\FlowFactory;
use App\Models\Flow;
use App\Models\FlowNode;
use App\Repositories\FlowRepositories;
use Illuminate\Support\Str;

final class Builder
{
    public function __construct(
        private readonly FlowFactory $flowFactory,
        private readonly FlowRepositories $flowRepo,
        private readonly FlowEngine $engine,
    ) {}

    /**
     * 创建流程实例 + 初始节点
     */
    public function create(string $type, array $inputs): Flow
    {
        $strategy = $this->flowFactory->for($type);
        $template = $strategy->selectTemplate($inputs);

        // 读取模板节点树（这里简化直接读取第一层节点做快照）
        $templateModel = $template->nodeTemplate()->with(['children', 'conditionNode'])->first();
        if (! $templateModel) {
            throw new \RuntimeException('模板未配置节点');
        }
        $templateSnapshot = $templateModel->toArray();

        // 模板树可按策略动态增强
        $templateSnapshot = $strategy->enrichNodeRules($templateSnapshot, $inputs);

        // 创建 flow
        $flow = $this->flowRepo->store([
            'title'                       => $strategy->makeTitle($inputs),
            'business_type'               => $template->type,
            'business_id'                 => (string) ($inputs['business_id'] ?? Str::ulid()->toBase32()),
            'parent_flow_id'              => $inputs['parent_flow_id'] ?? null,
            'parent_node_id'              => $inputs['parent_node_id'] ?? null,
            'level'                       => $inputs['parent_flow_id'] ? Level::SUBFLOW->value : Level::MAIN->value,
            'business_snapshot'           => $strategy->buildBusinessSnapshot($inputs),
            'status'                      => Status::CREATE->value,
            'flow_node_template_snapshot' => $templateSnapshot,
            'callback'                    => $template->callback       ?? null,
            'applicant_type'              => $inputs['applicant_type'] ?? 'user',
            'applicant_id'                => (string) ($inputs['applicant_id'] ?? '0'),
            'extend'                      => $inputs['extend'] ?? null,
            'flow_template_id'            => $template->id,
        ]);

        // 基于模板快照实例化节点（简化：拍平成线性 depth）
        $nodes = $this->linearizeTemplate($templateSnapshot);

        $depth = 1;
        foreach ($nodes as $nodeTpl) {
            /** @var FlowNode $node */
            $node = FlowNode::query()->create([
                'parent_id' => $nodeTpl['parent_id'] ?? null,
                'depth'     => $depth++,
                'name'      => $nodeTpl['name'] ?? ucfirst($nodeTpl['type']),
                'type'      => $nodeTpl['type'],
                'rules'     => $nodeTpl['rules'] ?? [],
                'status'    => $nodeTpl['type'] === 'start' ? FlowNodeTaskStatus::PROCESS->value : FlowNodeTaskStatus::SKIP->value,
                'callback'  => $nodeTpl['callback'] ?? null,
                'flow_id'   => $flow->id,
                'extend'    => null,
            ]);
        }

        // 引擎进入 process（处理 start 节点）
        $flow->status = Status::PROCESS->value;
        $flow->save();

        $startNode = FlowNode::query()->where('flow_id', $flow->id)->where('type', 'start')->first();
        if ($startNode) {
            $this->engine->processNode($flow, $startNode);
        }

        return $flow;
    }

    // 将模板树拍平成线性节点数组（按顺序：start -> chain children）
    private function linearizeTemplate(array $tpl): array
    {
        $result = [];
        $this->dfs($tpl, null, $result);

        return $result;
    }

    private function dfs(array $node, ?string $parentId, array &$result): void
    {
        $copy              = $node;
        $copy['parent_id'] = $parentId;
        $result[]          = $copy;
        foreach ($node['children'] ?? [] as $child) {
            $this->dfs($child, $copy['id'] ?? null, $result);
        }
    }
}
