<?php

namespace CodyMoorhouse\Chronicle\Requests\Notes;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/* Models */
use CodyMoorhouse\Chronicle\Models\Note;

class DestroyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $note = Note::find($this->route('note'));
        return $note->user_id == Auth::id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
