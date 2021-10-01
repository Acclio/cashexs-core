<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BidRequest extends FormRequest
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
            'selling_currency_id' => 'required|integer',
            'buying_currency_id' => 'required|integer',
            'beneficiary_id' => 'required|integer',
            'amount' => 'required|numeric|min:1',
            'rate' => 'required|numeric|min:1',
            'status' => 'integer|min:0|max:3',
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
            'selling_currency_id.required' => "Please select currency to be sold",
            'buying_currency_id.required' => "Please select currency to be bought",
            'beneficiary_id.required' => "Please select a beneficiary",
            'amount.required' => "Amount is required",
            'amount.min' => "Amount cannot be less than 1",
            'amount.numeric' => "Amount has to be a number",
            'rate.required' => "Rate is required",
            'rate.min' => "Rate cannot be less than 1",
            'rate.numeric' => "Rate has to be a number",
        ];
    }
}
