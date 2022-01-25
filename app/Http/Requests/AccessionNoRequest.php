<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AccessionNoRequest extends FormRequest
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

        $baseRules['accession_no'] = [
            'required', 'string', 'max:45', Rule::exists('accessions')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })
        ];

        return $baseRules;
    }
}
