<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
        Log::info($this);
        return [
            'id' => 'required|integer',
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'phone' => 'required|string|unique:users,phone,'.$this->id,
            'gender' => 'required|integer|min:0|max:1',
            'dob' => 'required|date',
            'country_id' => 'required|integer',
            'state_id' => 'required|integer',
            'city_id' => 'required|integer',
            'address' => 'required|string',
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
            'id.required' => "User ID is required",
            'firstname.required' => "Firstname is required",
            'lastname.required' => "Lastname is required",
            'phone.required' => "Phone number is required",
            'phone.unique' => "Phone number must be unique, this phone number already exist in our records",
            'dob.required' => "Date of birth is required",
            'dob.date' => "Date of birth must be a well formed date (YYYY-MM-DD)",
            'country_id.required' => "Country is required",
            'state_id.required' => "State is required",
            'city_id.required' => "City is required",
            'address.required' => "Address is required",
        ];
    }
}
