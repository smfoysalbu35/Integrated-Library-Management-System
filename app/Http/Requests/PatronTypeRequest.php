<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PatronTypeRequest extends FormRequest
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

        if($this->getMethod() === 'POST')
        {
            $baseRules['name'] = [
                'required', 'string', 'max:100', Rule::unique('patron_types')->where(function ($query) {
                    return $query->whereNull('deleted_at');
                })
            ];
        }

        if($this->getMethod() === 'PUT' || $this->getMethod() === 'PATCH')
        {
            $baseRules['name'] = [
                'required', 'string', 'max:100', Rule::unique('patron_types')->ignore($this->segment(2))->where(function ($query) {
                    return $query->whereNull('deleted_at');
                })
            ];
        }

        $baseRules['fines'] = 'required|numeric|gt:0';
        $baseRules['no_of_borrow_allowed'] = 'required|integer|gt:0';
        $baseRules['no_of_day_borrow_allowed'] = 'required|integer|gt:0';
        $baseRules['no_of_reserve_allowed'] = 'required|integer|gt:0';
        $baseRules['no_of_day_reserve_allowed'] = 'required|integer|gt:0';

        return $baseRules;
    }
}
