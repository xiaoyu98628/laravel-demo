<?php

declare(strict_types=1);

namespace other\Flow3;

use App\Repositories\FlowRepositories;
use other\Flow3\Factory\FlowFactory;

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
