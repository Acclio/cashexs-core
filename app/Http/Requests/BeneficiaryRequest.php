<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BeneficiaryRequest extends FormRequest
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
            'name' => 'required|string',
            'bank_id' => 'required|integer',
            'country_id' => 'required|integer',
            'account_no' => 'required|numeric',
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
            'name.required' => "Beneficiary name is required",
            'bank_id.required' => "Bank is required",
            'country_id.required' => "Country is required",
            'account_no.required' => "Beneficiary account number is required",
            'account_no.numeric' => "Beneficiary account number is required to be digits only",
        ];
    }
}
