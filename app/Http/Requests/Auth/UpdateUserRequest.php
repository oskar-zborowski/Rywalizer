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
            'first_name' => 'nullable|string|alpha|max:30',
            'last_name' => 'nullable|string|alpha|max:30',
            'email' => 'unique:users,email,' . $user->id,
            'birth_date' => 'nullable|string|date|size:10',
            'address_coordinates' => 'nullable|string|size:15',
            'telephone' => 'unique:users,telephone,' . $user->id,
            'facebook_profile' => 'unique:users,facebook_profile,' . $user->id,
            'instagram_profile' => 'unique:users,instagram_profile,' . $user->id,
            'gender_type_id' => 'nullable|integer|exists:gender_types,id'
        ];
    }
}
