<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientStoreRequest extends FormRequest
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
            'name' => 'required|string|min:1|max:50|unique:mysql.clients,name',
            'description' => 'required|string|min:1|max:500',
            'address' => 'required|string|min:1|max:150',
            'city' => 'required|string|min:1|max:150',
            'country' => 'required|string|min:1|max:150',
            'id_customer' => 'required|string|min:1|max:150',
        ];
    }
}
