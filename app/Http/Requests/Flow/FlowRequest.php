<?php

declare(strict_types=1);

namespace App\Http\Requests\Flow;

use Illuminate\Foundation\Http\FormRequest;

class FlowRequest extends FormRequest
{
    /**
     * 验证器之前的操作
     * @return void
     */
    public function prepareForValidation(): void {}

    public function rules(): array
    {
        return [
            'is_draft'    => 'required|boolean',
            'business_id' => 'required|string',
        ];
    }

    public function attributes(): array
    {
        return [];
    }

    public function messages(): array
    {
        return [
            'is_draft.required'    => '参数[is_draft]不能为空',
            'is_draft.boolean'     => '参数[business_id]为boolean类型',
            'business_id.required' => '参数[business_id]不能为空',
            'business_id.string'   => '参数[business_id]为string类型',
        ];
    }
}
