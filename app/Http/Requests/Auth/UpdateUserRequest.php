<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/**
 * Klasa z zasadami walidacji podczas uzupełniania danych użytkownika, bądź też aktualizacji już istniejących
 */
class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {

        /** @var User $user */
        $user = Auth::user();

        return [
            'first_name' => 'nullable|alpha|max:30',
            'last_name' => 'nullable|alpha|max:30',
            'email' => 'nullable|unique:users,email,' . $user->id,
            'telephone' => 'nullable|unique:users,telephone,' . $user->id,
            'birth_date' => 'nullable|date_format:Y-m-d',
            'address_coordinates' => 'nullable|string|size:21',
            'facebook_profile' => 'nullable|url|max:255',
            'instagram_profile' => 'nullable|url|max:255',
            'website' => 'nullable|url|max:255'
        ];
    }
}
