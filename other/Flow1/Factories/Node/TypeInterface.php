<?php

declare(strict_types=1);

namespace other\Flow1\Factories\Node;

/**
 * 节点类型接口
 */
interface TypeInterface
{
    public function setTemplate(array $template): static;

    public function generateNode(array $inputs): array;

    public function getType(): string;
}
