<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/**
 * Klasa z zasadami walidacji podczas uzupełniania danych użytkownika, bądź też aktualizacji już istniejących
 */
class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        /** @var User $user */
        $user = Auth::user();

        return [
            'first_name' => 'string|alpha|max:30|nullable',
            'last_name' => 'string|alpha|max:30|nullable',
            'email' => 'unique:users,email,' . $user->id,
            'avatar' => 'image|size:2048|nullable',
            'birth_date' => 'string|date|size:10|nullable',
            'address_coordinates' => 'string|size:15|nullable',
            'telephone' => 'unique:users,telephone,' . $user->id,
            'facebook_profile' => 'unique:users,facebook_profile,' . $user->id,
            'gender_type_id' => 'integer|exists:gender_types,id|nullable'
        ];
    }
}
