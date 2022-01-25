<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PatronAccountRegisterRequest extends FormRequest
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
        $baseRules = [];

        $baseRules['patron_no'] = [
            'required', 'string', 'alpha_dash', 'max:45', Rule::exists('patrons')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })
        ];

        $baseRules['email'] = [
            'required', 'string', 'email', 'max:100', Rule::unique('patron_accounts')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })
        ];

        $baseRules['password'] = 'required|string|min:8|max:100|confirmed';

        return $baseRules;
    }
}
