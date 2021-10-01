<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OfferRequest extends FormRequest
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
            'bid_id' => 'required|integer',
            'beneficiary_id' => 'required|integer',
            'offer' => 'required|numeric|min:1',
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
            'bid_id.required' => "Bid ID is required",
            'beneficiary_id.required' => "Please select a beneficiary. Beneficiary ID is required",
            'offer.required' => "Offer amount is required",
            'offer.min' => "Offer amount cannot be less than 1",
            'offer.numeric' => "Offer amount has to be a number",
        ];
    }
}
