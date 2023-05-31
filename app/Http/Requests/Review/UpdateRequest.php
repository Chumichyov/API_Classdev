<?php

namespace App\Http\Requests\Review;

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
            'color' => ['required', 'string'],
            'start' => ['required', 'integer'],
            'end' => ['required', 'integer'],
            'title' => ['required', 'string'],
            'description' => ['string', 'nullable'],
        ];
    }
}
