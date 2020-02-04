<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RateStoreRequest extends FormRequest
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
            'ido' => 'required|numeric',
            'range_date' => 'required|string',
            'rate_normal' => 'required|numeric|between:0,99.99',
            'rate_reduced' => 'required|numeric|between:0,99.99',
            'rate_night' => 'required|numeric|between:0,99.99',
        ];
    }
}
