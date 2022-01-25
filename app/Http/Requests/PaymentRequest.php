<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentRequest extends FormRequest
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
            'patron_no' => [
                'required', 'string', 'alpha_dash', 'max:45', Rule::exists('patrons')->where(function ($query) {
                    return $query->whereNull('deleted_at');
                })
            ],

            'payment' => 'required|numeric|gt:0',
        ];
    }
}
