<?php

declare(strict_types=1);

namespace App\Http\Requests\ApprovalTemplate;

use App\Constants\Enums\ApprovalTemplate\FlowCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Service\Common\Library\Rpc\Service\Http\HttpRequest;

class ApprovalTemplateRequest extends FormRequest
{
    /**
     * 验证器之前的操作
     * @return void
     */
    public function prepareForValidation(): void
    {
        $this->merge([
            'callback' => in_array($this->input('flow_code'), [FlowCode::WORKFLOW->value, FlowCode::PROJECT->value])
                ? null
                : $this->input('callback'),
        ]);
    }

    public function rules(): array
    {
        return match (Str::lower($this->method())) {
            HttpRequest::POST => [
                'flow_code'     => 'required|string|max:50',
                'name'          => 'required|string|max:50',
                'callback'      => 'array|required_if:flow_code,'.implode(',', FlowCode::needCallback()),
                'remark'        => 'nullable|string|max:255',
                'node_template' => 'required|array',
            ]
        };
    }

    public function attributes(): array
    {
        return [
            'flow_code' => '流程标识',
            'name'      => '名称',
            'callback'  => '回调',
            'remark'    => '备注',
            'status'    => '状态',
        ];
    }

    public function messages(): array
    {
        return [
            'callback.array'       => '回调配置类型错误',
            'callback.required_if' => '回调配置不能为空',
        ];
    }
}
