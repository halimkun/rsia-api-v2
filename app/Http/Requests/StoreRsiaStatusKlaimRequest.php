<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRsiaStatusKlaimRequest extends FormRequest
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
        // exist in bridging_sep table
        return [
            'no_sep'   => ['required', 'string', 'max:255', 'exists:bridging_sep,no_sep'],
            'no_rawat' => ['required', 'string', 'max:255', 'exists:bridging_sep,no_rawat'],
            'status'   => ['required', 'string', 'max:255'],
            'feedback' => ['string',],
        ];
    }
}
