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
    public function prepareForValidation(): void
    {
        $this->merge([
            'callback' => in_array($this->input('type'), [Type::WORKFLOW->value, Type::PROJECT->value])
                ? null
                : $this->input('callback'),
        ]);
    }

    public function rules(): array
    {
        return match (Str::lower($this->method())) {
            HttpRequest::POST, HttpRequest::PUT => [
                'type'          => 'required|string|max:50|in:'.implode(',', Type::values()),
                'name'          => 'required|string|max:50',
                'callback'      => 'array|required_if:flow_code,'.implode(',', Type::needCallback()),
                'remark'        => 'nullable|string|max:255',
                'node_template' => 'required|array',
            ],
        };
    }

    public function attributes(): array
    {
        return [
            'type'     => '类型',
            'name'     => '名称',
            'callback' => '回调',
            'remark'   => '备注',
            'status'   => '状态',
        ];
    }

    public function messages(): array
    {
        return [
            'type.in'                => '模版类型错误',
            'callback.array'         => '回调配置类型错误',
            'callback.required_if'   => '回调配置不能为空',
            'node_template.required' => '节点不能为空',
            'node_template.array'    => '节点配置错误',
        ];
    }
}
