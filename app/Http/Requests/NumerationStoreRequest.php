<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NumerationStoreRequest extends FormRequest
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
            'start_numbers' => 'required',
            'end_numbers' => 'required',
            'types' => 'required'
        ];
    }

    public function messages()
    {
        return [
            '*' => 'La lista de rangos numéricos se encuentra vacia.'
        ];
    }
}
