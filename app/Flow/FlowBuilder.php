<?php

declare(strict_types=1);

namespace App\Flow;

/**
 * 负责创建审批流程
 */
final class FlowBuilder
{
    /** @var string 审批流类型 */
    private string $type;

    /**
     * @param  string  $type
     * @return void
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function flow(): self
    {
        return $this;
    }

    public function node(): self
    {
        return $this;
    }

    public function task(): self
    {
        return $this;
    }
}
