<?php

declare(strict_types=1);

namespace App\Flow1\Factories\Flow;

/**
 * 审批流程接口
 */
interface TypeInterface
{
    public function setTemplate(array $template): static;

    public function generateName(array $inputs): string;

    public function generateBusinessSnapshot(array $inputs): array;

    public function generateFlow(array $inputs): array;
}
