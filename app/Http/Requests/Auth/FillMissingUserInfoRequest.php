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
            'email' => 'string|email|max:254|nullable',
            'gender_type_id' => 'integer|between:1,2|nullable',
            'birth_date' => 'string|date|size:10|nullable'
        ];

        // 'avatar' => 'image|size:2048|nullable',
    }
}
