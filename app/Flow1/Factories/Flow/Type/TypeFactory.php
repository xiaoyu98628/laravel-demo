<?php

declare(strict_types=1);

namespace App\Flow1\Factories\Flow\Type;

abstract class TypeFactory
{
    protected array $template;

    /**
     * 设置模版
     * @param  array  $template
     * @return $this
     */
    public function setTemplate(array $template): static
    {
        $this->template = $template;

        return $this;
    }
}
