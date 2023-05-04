<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [
            'name' => [
                'required',
                'string',
                'max:100',
                'min:3',
            ],
            'email' => [
                'required',
                'email',
                'unique:users',
            ],
            'password' => [
                'required',
                'min:6',
                'max:15',
            ],
            'device_name' => [
                'required',
                'string',
                'max:200',
            ]
        ];
        return $rules;
    }
}
