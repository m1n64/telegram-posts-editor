<?php

namespace Modules\Posts\Http\Requests\Api\Posts;

use Illuminate\Foundation\Http\FormRequest;

class SaveRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'post_id' => ['nullable', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'telegram_key_id' => ['required', 'integer'],
            'photos' => ['array', 'nullable'],
            'photos.*' => ['file', 'mimes:jpeg,jpg,png'],
        ];
    }
}
