<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
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

        $baseRules['last_name'] = 'required|string|min:2|max:45';
        $baseRules['first_name'] = 'required|string|min:2|max:45';

        if($this->has('middle_name'))
            $baseRules['middle_name'] = 'nullable|string|min:2|max:45';

        $baseRules['email'] = [
            'required', 'string', 'email', 'max:100', Rule::unique('users')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })
        ];

        $baseRules['password'] = 'required|string|min:8|max:100|confirmed';

        return $baseRules;
    }
}
