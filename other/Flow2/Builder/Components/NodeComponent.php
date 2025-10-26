<?php
declare(strict_types=1);

namespace other\Flow2\Builder\Components;

abstract class NodeComponent
{
    public function __construct(
        public string $type,
        public string $name,
        public array $rules = [],
        public ?string $description = null,
        public array $children = [], // 对于组合节点
    ) {}
}
