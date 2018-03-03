<?php

namespace CodyMoorhouse\Secretary\Requests\Sections;

use Illuminate\Foundation\Http\FormRequest;

class GetRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'tag'   =>  'required|exists:section,tag'
        ];
    }/**
 * Get the error messages for the defined validation rules.
 *
 * @return array
 */
public function messages()
{
    return [
        'tag.required' => 'A tag is required',
        'tag.exists'  => 'The specified tag does not exist'
    ];
}
}
