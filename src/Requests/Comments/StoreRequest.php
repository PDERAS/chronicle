<?php

namespace Pderas\Chronicle\Requests\Comments;

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
            'description'   =>  'required|string',
            'note_id'       =>  'required|exists:' . $notes_table . ',id'
        ];
    }
}
