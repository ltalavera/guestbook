<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class EntryRequest extends FormRequest
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
            'guest_type_id'     => 'required|integer',
            'guest_document_id' => 'required|alpha_num|max:20',
            'guest_full_name'   => 'required',
            'guest_signature'   => 'required',
            'entry_in'          => 'required|date'
        ];
    }
}
