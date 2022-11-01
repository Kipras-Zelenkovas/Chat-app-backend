<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUser extends FormRequest
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
        return [
            "email"     => "required|email|unique:users|max:35|string",
            "password"  => "required|min:8|max:25|string",
            "name"      => "required|string",
            "surname"   => "required|string",
            "username"  => "required|string"
        ];
    }
}
