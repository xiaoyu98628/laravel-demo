<?php

declare(strict_types=1);

namespace App\Flow;

use App\Flow\Factories\FlowFactory;

/**
 * 负责创建审批流程
 */
final class Builder
{
    /** @var string 审批流类型 */
    private string $type;

    /** @var array 业务参数 */
    private array $inputs;

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
     * @return $this
     * @throws \Exception
     */
    public function flow(): self
    {
        $flow = FlowFactory::make($this->type)->generateFlow($this->inputs);
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
