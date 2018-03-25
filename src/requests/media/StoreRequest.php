<?php

namespace CodyMoorhouse\Chronicle\Requests\Media;

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
        $notes_table = config('chronicle.notes_table');

        return [
            'file'      =>  'required|file',
            'note_id'   =>  'required|exists:' . $notes_table . ',id'
        ];
    }
}
