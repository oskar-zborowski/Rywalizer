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
            'encrypted_email' => 'nullable|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|max:2048',
            'birth_date' => 'nullable|string|date|size:10',
            'address_coordinates' => 'nullable|string|size:15',
            'encrypted_telephone' => 'nullable|unique:users,telephone,' . $user->id,
            'encrypted_facebook_profile' => 'nullable|unique:users,facebook_profile,' . $user->id,
            'encrypted_instagram_profile' => 'nullable|unique:users,instagram_profile,' . $user->id,
            'gender_type_id' => 'nullable|integer|exists:gender_types,id'
        ];
    }
}
