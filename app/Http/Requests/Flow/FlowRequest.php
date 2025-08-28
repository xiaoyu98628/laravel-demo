<?php

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
        return [];
    }

    public function attributes(): array
    {
        return [];
    }

    public function messages(): array
    {
        return [];
    }
}
