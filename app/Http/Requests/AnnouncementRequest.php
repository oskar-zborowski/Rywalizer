<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnnouncementRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'facility_id' => 'nullable|integer|exists:facilities,id',
            'facility_name' => 'nullable|string|max:200',
            'facility_street' => 'nullable|string|max:80',
            'sport_id' => 'required|integer|between:46,57',
            'start_date' => 'required|date_format:Y-m-d H:i:s',
            'end_date' => 'required|date_format:Y-m-d H:i:s',
            'visible_at' => 'nullable|date_format:Y-m-d H:i:s',
            'ticket_price' => 'required|integer',
            'game_variant_id' => 'required|integer|between:77,78',
            'gender_id' => 'nullable|integer|between:9,10',
            'minimum_skill_level_id' => 'nullable|exists:minimum_skill_levels,id',
            'age_category_id' => 'nullable|integer|between:79,82',
            'minimal_age' => 'nullable|integer',
            'maximal_age' => 'nullable|integer',
            'description' => 'nullable|string|max:1500',
            'announcement_type_id' => 'nullable|integer|between:83,84',
            'is_automatically_approved' => 'required|boolean',
            'is_public' => 'required|boolean',
            'payment_type_ids' => 'required|array'
        ];
    }
}
