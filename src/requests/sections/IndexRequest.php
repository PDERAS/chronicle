<?php

namespace CodyMoorhouse\Chronicle\Requests\Sections;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
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
            'access_type'               =>  'in:private,public',
            'section_type'              =>  'in:note,document',
            'title'                     =>  'string',
            'tag'                       =>  'string',
            'is_commenting_allowed'     =>  'boolean',
            'is_attachments_allowed'    =>  'boolean',
            'is_tasks_allowed'          =>  'boolean',
            'is_approval_required'      =>  'boolean',
            'paginate'                  =>  'numeric|min:1'
        ];
    }
}
