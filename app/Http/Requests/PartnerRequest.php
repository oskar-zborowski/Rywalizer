<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PartnerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'business_name' => 'nullable|string|max:200',
            'contact_email' => 'nullable|string|email|max:254',
            'telephone' => 'nullable|string|max:24',
            'facebook_profile' => 'nullable|url|max:255',
            'instagram_profile' => 'nullable|url|max:255',
            'website' => 'nullable|url|max:255',
            // 'partner_type_id' => 'required|integer|size:59',
            'visible_name_id' => 'required|integer|between:61,62',
            'visible_image_id' => 'required|integer|between:61,62',
            'visible_email_id' => 'required|integer|between:61,62',
            'visible_telephone_id' => 'required|integer|between:61,62',
            'visible_facebook_id' => 'required|integer|between:61,62',
            'visible_instagram_id' => 'required|integer|between:61,62',
            'visible_website_id' => 'required|integer|between:61,62',
        ];
    }
}
