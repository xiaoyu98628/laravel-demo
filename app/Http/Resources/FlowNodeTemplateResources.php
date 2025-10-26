<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Constants\Enums\FlowNodeTemplate\Type;
use Illuminate\Http\Request;
use Service\Common\Base\BaseResource;

class FlowNodeTemplateResources extends BaseResource
{
    /**
     * 关联模型字段
     */
    public function getWithFields(Request $request): array
    {
        return [
            'children'       => new self($this->whenLoaded('children')),
            'condition_node' => self::collection($this->whenLoaded('conditionNode')),
        ];
    }

    /**
     * 自定义字段
     */
    public function getCustomFields(Request $request): array
    {
        $data = [];
        if ($this->checkResourceFields('type_str')) {
            $data['type_str'] = Type::tryFrom($this->type)->label();
        }
        return $data;
    }
}
