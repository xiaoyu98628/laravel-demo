<?php

declare(strict_types=1);

namespace App\Flow\Factories\Node\Type;

use App\Constants\Enums\FlowNode\Status;
use App\Constants\Enums\FlowNode\Type;
use App\Flow\Factories\Node\TypeInterface;
use Illuminate\Support\Arr;

class ApprovalFactory extends TypeFactory implements TypeInterface
{
    public function generateNode(array $inputs): array
    {
        return [
            'parent_id' => Arr::get($inputs, 'parent_id'),
            'depth'     => Arr::get($this->template, 'depth'),
            'name'      => Arr::get($this->template, 'name'),
            'type'      => Type::APPROVAL->value,
            'rules'     => Arr::get($this->template, 'rules'),
            'status'    => Status::PROCESS->value,
            'callback'  => Arr::get($this->template, 'callback'),
            'flow_id'   => Arr::get($this->template, 'flow_id'),
        ];
    }
}
