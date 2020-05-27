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
        return [
            'type' => 'required|max:20',
            'address' => 'required|max:255',
            'city' => 'required|max:20',
            'state' => 'required|max:20',
            'zipcode' => 'required|max:20',
            'phone' => 'required|max:20',
            'email' => 'required|email',
            'user_id' => 'required'
        ];
    }
}
