<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientUpdateRequest extends FormRequest
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
        $id = $this->route('cliente');
        return [
            'name' => 'required|string|min:1|max:50|unique:mysql.clients,name,'.$id,
            'description' => 'required|string|min:1|max:500',
            'address' => 'required|string|min:1|max:150',
            'city' => 'required|string|min:1|max:150',
            'country' => 'required|string|min:1|max:150',
        ];
    }
}
