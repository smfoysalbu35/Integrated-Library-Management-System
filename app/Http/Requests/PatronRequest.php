<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PatronRequest extends FormRequest
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
            $baseRules['patron_no'] = [
                'required', 'string', 'alpha_dash', 'max:45', Rule::unique('patrons')->where(function ($query) {
                    return $query->whereNull('deleted_at');
                })
            ];
        }

        if($this->getMethod() === 'PUT' || $this->getMethod() === 'PATCH')
        {
            $baseRules['patron_no'] = [
                'required', 'string', 'alpha_dash', 'max:45', Rule::unique('patrons')->ignore($this->segment(2))->where(function ($query) {
                    return $query->whereNull('deleted_at');
                })
            ];
        }

        $baseRules['last_name'] = 'required|string|min:2|max:45';
        $baseRules['first_name'] = 'required|string|min:2|max:45';

        if($this->has('middle_name') && $this->get('middle_name'))
            $baseRules['middle_name'] = 'nullable|string|min:2|max:45';

        $baseRules['house_no'] = 'required|string|max:100';
        $baseRules['street'] = 'required|string|max:100';
        $baseRules['barangay'] = 'required|string|max:100';
        $baseRules['municipality'] = 'required|string|max:100';
        $baseRules['province'] = 'required|string|max:100';

        $baseRules['contact_no'] = 'required|string|min:10|max:45';
        $baseRules['patron_type_id'] = 'required|integer|exists:patron_types,id';
        $baseRules['section_id'] = 'required|integer|exists:sections,id';

        if($this->hasFile('image'))
            $baseRules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048';

        return $baseRules;
    }

    public function messages()
    {
        return [
            'patron_type_id.required' => 'The patron type field is required.',
            'patron_type_id.integer' => 'The patron type must be an integer.',
            'patron_type_id.exists' => 'The selected patron type is invalid.',

            'section_id.required' => 'The section field is required.',
            'section_id.integer' => 'The section must be an integer.',
            'section_id.exists' => 'The selected section is invalid.',
        ];
    }
}
