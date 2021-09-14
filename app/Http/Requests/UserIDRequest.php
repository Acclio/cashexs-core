<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserIDRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'identification_type' => 'required|string',
            'identification_no' => 'required|string',
            'identification' => 'required|image|mimes:jpeg,png,jpg,gif|max:10096'
        ];
    }


    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'identification_type.required' => "Identification type is required",
            'identification_no.required' => "Identification number is required",
            'identification.required' => "Identification is required",
            'identification.image' => "Identification must be an image",
            'identification.mimes' => "Identification must be a jpeg, png or gif",
            'identification.max' => "Identification image cannot be more than 10096KB",
        ];
    }
}
