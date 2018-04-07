<?php

namespace CodyMoorhouse\Chronicle\Requests\Notes;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
        $sections_table = config('chronicle.sections_table');

        return [
            'description'   =>  'required|string',
            'section_tag'   =>  'required|exists:' . $sections_table . ',tag',
            'section_ref'   =>  'required|string',
        ];
    }
}
