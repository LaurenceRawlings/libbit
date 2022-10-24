<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreResourceRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:note,link'],
            'content' => ['exclude_if:type,note', 'required', 'url'],
            'tags.*' => ['alpha_dash', 'max:20'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'tags.*.alpha_dash' => 'Tags must be alphanumeric, dashes and underscores may be used.',
            'tags.*.max' => 'Tags must be less than 20 characters.',
        ];
    }
}
