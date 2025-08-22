<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Service\Common\Base\BaseResource;

class ApprovalNodeTemplateResources extends BaseResource
{
    /**
     * 关联模型字段
     */
    public function getWithFields(Request $request): array
    {
        return [
            'children' => $this->children ? [] : self::collection($this->children),
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
