<?php

declare(strict_types=1);

namespace App\Flow\Factories\Node;

/**
 * 节点类型接口
 */
interface TypeInterface
{
    public function setTemplate(array $template): static;

    public function generateNode(array $inputs): array;
}
