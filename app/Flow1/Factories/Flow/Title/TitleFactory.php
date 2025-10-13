<?php

declare(strict_types=1);

namespace App\Flow1\Factories\Flow\Title;

use Illuminate\Support\Arr;

abstract class TitleFactory
{
    public function generate(array $inputs, array $template): string
    {
        return 'xxx 发起的'.Arr::get($template, 'name');
    }
}
