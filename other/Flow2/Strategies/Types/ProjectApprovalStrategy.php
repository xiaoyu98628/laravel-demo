<?php

declare(strict_types=1);

namespace other\Flow2\Strategies\Types;

use App\Models\FlowTemplate;
use App\Repositories\FlowTemplateRepositories;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use other\Flow2\Contracts\ApprovalTypeStrategyInterface;

final class ProjectApprovalStrategy implements ApprovalTypeStrategyInterface
{
    public function __construct(private readonly FlowTemplateRepositories $templates) {}

    public function selectTemplate(array $inputs): FlowTemplate
    {
        $tpl = $this->templates->query()
            ->where('type', 'project')
            ->where('status', 'enable')
            ->first();
        if (! $tpl) {
            throw new ModelNotFoundException('未找到启用的项目审批模板');
        }

        return $tpl;
    }

    public function buildBusinessSnapshot(array $inputs): array
    {
        return [
            'project_id' => $inputs['project_id'] ?? '',
            'budget'     => $inputs['budget']     ?? 0,
            'owner'      => $inputs['owner']      ?? '',
        ];
    }

    public function makeTitle(array $inputs): string
    {
        return '项目立项审批：'.($inputs['project_name'] ?? '未命名项目');
    }

    public function enrichNodeRules(array $nodeTemplateTree, array $inputs): array
    {
        // 示例：预算>=某阈值时走财务子流程
        return $nodeTemplateTree;
    }
}
