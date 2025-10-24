<?php

declare(strict_types=1);

namespace App\Flow4\Strategy\Strategies;

use App\Flow4\Strategy\AbstractApprovalStrategy;
use App\Models\Flow;
use App\Models\FlowNode;

/**
 * 项目审批策略
 */
class ProjectApprovalStrategy extends AbstractApprovalStrategy
{
    public function process(Flow $flow, FlowNode $node, array $data): void
    {
        $projectType = $flow->extend['project_type'] ?? '';

        switch ($projectType) {
            case 'urgent':
                $this->processUrgentProject($flow, $node, $data);
                break;
            case 'normal':
                $this->processNormalProject($flow, $node, $data);
                break;
            default:
                $this->processGeneralProject($flow, $node, $data);
        }
    }

    private function processUrgentProject(Flow $flow, FlowNode $node, array $data): void
    {
        // 紧急项目处理逻辑 - 可能需要缩短审批时间
    }

    private function processNormalProject(Flow $flow, FlowNode $node, array $data): void
    {
        // 普通项目处理逻辑
    }

    private function processGeneralProject(Flow $flow, FlowNode $node, array $data): void
    {
        // 一般项目处理逻辑
    }
}
