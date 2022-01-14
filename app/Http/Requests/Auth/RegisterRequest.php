<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Klasa z zasadami walidacji podczas procesu rejestracji
 */
class RegisterRequest extends FormRequest
{
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'first_name' => 'required|alpha|max:30',
            'last_name' => 'required|alpha|max:30',
            'email' => 'unique:users',
            'birth_date' => 'required|date_format:Y-m-d',
            'accepted_agreements' => 'required|array'
        ];
    }
}
