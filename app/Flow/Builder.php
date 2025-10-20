<?php

declare(strict_types=1);

namespace App\Flow;

use App\Console\Commands\Arr;
use App\Flow\Factory\FlowFactory;

final class Builder
{
    public function __construct(
        private FlowFactory $flowFactory
    ) {}

    /**
     * @param  array  $inputs
     * @return void
     * @throws \Exception
     */
    public function build(array $inputs)
    {
        $factory  = $this->flowFactory->make(Arr::get($inputs, 'type'));
        $template = $factory->selectTemplate($inputs);

        // 读取模板节点树（这里简化直接读取第一层节点做快照）
        $nodeTemplate = $template->nodeTemplate()->with(['children', 'conditionNode'])->whereNull('parent_id')->first();
        if (empty($nodeTemplate)) {
            throw new \RuntimeException('模板未配置节点');
        }
    }
}
