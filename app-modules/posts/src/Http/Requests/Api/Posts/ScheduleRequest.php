<?php

namespace Modules\Posts\Http\Requests\Api\Posts;


class ScheduleRequest extends SaveRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'publish_date' => ['required', 'integer'],
        ]);
    }
}
