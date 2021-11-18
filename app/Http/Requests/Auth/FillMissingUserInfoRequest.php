<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Klasa z zasadami walidacji podczas uzupełniania brakujących informacji o użytkowniku
 */
class FillMissingUserInfoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'present|string|email|max:254|nullable',
            'gender_type_id' => 'present|integer|between:1,2|nullable',
            'birth_date' => 'present|string|date|size:10|nullable'
        ];

        // 'avatar' => 'present|image|size:2048|nullable',
    }
}
