<?php

declare(strict_types=1);

namespace App\Flow3;

use App\Flow3\Factory\FlowFactory;
use App\Repositories\FlowRepositories;

final readonly class Builder
{
    public function __construct(
        private FlowRepositories $repositories,
        private FlowFactory $flowFactory,
    ) {}

    /**
     * @param  array  $inputs
     * @return void
     * @throws \Exception
     */
    public function build(string $type, array $inputs)
    {
        $factory  = $this->flowFactory->make($type);
        $template = $factory->flowTemplate($inputs);

        dd(123);


        // 存储审批流程数据
        $this->repositories->store($factory->buildFlow($inputs));

    }
}
