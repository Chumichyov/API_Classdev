<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:64'],
            'description' => ['sometimes', 'required', 'string', 'max:255'],
            'image' => ['image', 'mimes:png,jpg,jpeg,jfif', 'max:4096'],
        ];
    }
}
