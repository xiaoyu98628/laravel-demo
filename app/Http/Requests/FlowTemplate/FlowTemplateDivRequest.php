<?php

declare(strict_types=1);

namespace App\Http\Requests\FlowTemplate;

use App\Constants\Enums\FlowTemplate\Status;
use App\Constants\Enums\FlowTemplate\Type;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Service\Common\Library\Rpc\Service\Http\HttpRequest;

class FlowTemplateDivRequest extends FormRequest
{
    /**
     * 验证器之前的操作
     * @return void
     */
    public function prepareForValidation(): void {}

    public function rules(): array
    {
        return match (Str::lower($this->method())) {
            HttpRequest::PUT => [
                'status' => 'required|string|in :'.implode(',', Status::values()),
            ],
        };
    }

    public function attributes(): array
    {
        return [
            'status' => '状态',
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => '参数错误',
            'status.in'       => '状态类型错误',
        ];
    }
}
