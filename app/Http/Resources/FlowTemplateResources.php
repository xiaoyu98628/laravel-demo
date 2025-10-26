<?php

namespace App\Http\Resources;

use App\Constants\Enums\FlowTemplate\Type;
use Illuminate\Http\Request;
use Service\Common\Base\BaseResource;

class FlowTemplateResources extends BaseResource
{
    /**
     * 关联模型字段
     */
    public function getWithFields(Request $request): array
    {
        return [
            'node_template' => new FlowNodeTemplateResources($this->whenLoaded('nodeTemplate')),
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
