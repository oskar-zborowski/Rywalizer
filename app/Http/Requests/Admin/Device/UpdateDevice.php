<?php

namespace App\Http\Requests\Admin\Device;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateDevice extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.device.edit', $this->device);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'ip' => ['sometimes', 'string'],
            'uuid' => ['sometimes', 'string'],
            'os_name' => ['nullable', 'string'],
            'os_version' => ['nullable', 'string'],
            'browser_name' => ['nullable', 'string'],
            'browser_version' => ['nullable', 'string'],
            
        ];
    }

    /**
     * Modify input data
     *
     * @return array
     */
    public function getSanitized(): array
    {
        $sanitized = $this->validated();


        //Add your code for manipulation with request data here

        return $sanitized;
    }
}
