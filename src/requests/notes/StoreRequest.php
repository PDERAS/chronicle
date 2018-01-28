<?php

namespace CodyMoorhouse\Secretary\Requests\Notes;

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
        $sections_table = config('secretary.sections_table');

        return [
            'description'       =>  'required|string',
            'section_id'        =>  'required|exists:' . $sections_table . ',id',
            'section_ref_slug'  =>  'required|string',
        ];
    }
}
