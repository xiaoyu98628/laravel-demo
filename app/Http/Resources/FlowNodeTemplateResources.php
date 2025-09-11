<?php

declare(strict_types=1);

namespace App\Http\Resources;

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
            'children'       => $this->children ? new self($this->children) : null,
            'condition_node' => $this->conditionNode ? self::collection($this->conditionNode) : null,
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
