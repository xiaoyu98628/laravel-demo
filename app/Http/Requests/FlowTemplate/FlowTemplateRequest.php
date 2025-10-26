<?php

declare(strict_types=1);

namespace App\Http\Requests\FlowTemplate;

use App\Constants\Enums\FlowTemplate\Type;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Service\Common\Library\Rpc\Service\Http\HttpRequest;

class FlowTemplateRequest extends FormRequest
{
    /**
     * 验证器之前的操作
     * @return void
     */
    public function prepareForValidation(): void {}

    public function rules(): array
    {
        return match (Str::lower($this->method())) {
            HttpRequest::POST, HttpRequest::PUT => [
                'type'          => 'required|string|in:'.implode(',', Type::values()),
                'code'          => 'required|string|max:50',
                'name'          => 'required|string|max:50',
                'callback'      => 'array',
                'remark'        => 'nullable|string|max:255',
                'node_template' => 'required|array',
            ],
        };
    }

    public function attributes(): array
    {
        return [
            'type'     => '类型',
            'code'     => '标识',
            'name'     => '名称',
            'callback' => '回调配置',
            'remark'   => '备注',
            'status'   => '状态',
        ];
    }

    public function messages(): array
    {
        return [
            'type.in'                => '模版类型错误',
            'callback.array'         => '回调配置类型错误',
            'node_template.required' => '节点不能为空',
            'node_template.array'    => '节点配置错误',
        ];
    }
}
