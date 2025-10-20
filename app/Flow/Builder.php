<?php

declare(strict_types=1);

namespace App\Flow;

use App\Flow\Factory\FlowFactory;

final readonly class Builder
{
    public function __construct(
        private FlowFactory $flowFactory
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

        $flow = $factory->buildFlow($inputs);

        dd($flow);

    }
}
