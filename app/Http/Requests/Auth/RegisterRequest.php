<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Klasa z zasadami walidacji podczas procesu rejestracji
 */
class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'first_name' => 'required|string|alpha|max:30',
            'last_name' => 'required|string|alpha|max:30',
            'email' => 'unique:users',
            'gender_type_id' => 'integer|between:1,2|nullable',
            'birth_date' => 'required|string|date|size:10'
        ];
    }
}
