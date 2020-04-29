<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecurringChargeStoreRequest extends FormRequest
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
            'id_client' => 'required|string',
            'date_service_start' => 'required|string',
            'description' => 'required|string|min:1|max:200',
            'modality' => 'required',
            'date' => 'required_if:modality,==,1',
            'quantity' => 'required|numeric',
            'cost_unit' => 'required|numeric',
            'money_type' => 'required|string|min:1|max:10',
        ];
    }

    public function messages()
    {
        return [
            'date.required_if' => 'La fecha es obligatoria cuando el campo :other es Único.'
        ];
    }
}
