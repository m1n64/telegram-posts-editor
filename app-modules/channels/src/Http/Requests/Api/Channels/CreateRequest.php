<?php

namespace Modules\Channels\Http\Requests\Api\Channels;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'token' => ['required', 'string'],
            'channel_id' => ['required', 'string'],
        ];
    }
}
