<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignupRequest extends FormRequest
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
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'username' => 'required|string|unique:users,username',
            'email' => 'required|string|email|unique:users,email',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|string|confirmed|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
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
            'firstname.required' => "Firstname is required",
            'lastname.required' => "Lastname is required",
            'username.required' => "Username is required",
            'phone.required' => "Phone number is required",
            'phone.unique' => "Phone number must be unique, this phone number already exist in our records",
            'email.required' => "Email address is required",
            'email.unique' => "Email address must be unique, this email address already exist in our records",
            'email.email' => "Email address must be a well formed email address",
            'dob.required' => "Date of birth is required",
            'dob.date' => "Date of birth must be a well formed date (YYYY-MM-DD)",
            'country_id.required' => "Country is required",
            'state_id.required' => "State is required",
            'city_id.required' => "City is required",
            'address.required' => "Address is required",
            'password.required' => 'New password is required',
            'password.confirmed' => 'Please confirm new password',
            'password.min' => 'New password must be a minimum of 8 characters',
            'password.regex' => 'New password must be more than 8 characters long, should contain at-least 1 Uppercase, 1 Lowercase, 1 Numeric and 1 special character.',
        ];
    }
}
