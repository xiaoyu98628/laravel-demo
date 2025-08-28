<?php

namespace App\Http\Resources;

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
            'node_template' => $this->node_template ? null : new FlowNodeTemplateResources($this->node_template),
        ];
    }

    /**
     * 自定义字段
     */
    public function getCustomFields(Request $request): array
    {
        $data = [];
        if ($this->checkResourceFields('*')) {
            $data['*'] = '';
        }
        return $data;
    }
}
