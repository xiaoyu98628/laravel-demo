<?php

declare(strict_types=1);

namespace App\Flow2\Builder;

use App\Flow2\Builder\Components\CompositeNode;
use App\Flow2\Builder\Components\LeafNode;
use App\Flow2\Builder\Components\NodeComponent;

final class TemplateBuilder
{
    private ?NodeComponent $root = null;

    public function start(string $name = '开始'): self
    {
        $this->root = new LeafNode('start', $name);

        return $this;
    }

    public function next(NodeComponent $node): self
    {
        if (! $this->root) {
            throw new \RuntimeException('请先调用 start()');
        }
        $this->root = new CompositeNode($this->root->type, $this->root->name, $this->root->rules, $this->root->description, [$node]);

        return $this;
    }

    public function build(): NodeComponent
    {
        if (! $this->root) {
            throw new \RuntimeException('未初始化模板');
        }

        return $this->root;
    }
}
