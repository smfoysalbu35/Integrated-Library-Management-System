<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SectionRequest extends FormRequest
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
                'required', 'string', 'max:100', Rule::unique('sections')->where(function ($query) {
                    return $query->whereNull('deleted_at');
                })
            ];
        }

        if($this->getMethod() === 'PUT' || $this->getMethod() === 'PATCH')
        {
            $baseRules['name'] = [
                'required', 'string', 'max:100', Rule::unique('sections')->ignore($this->segment(2))->where(function ($query) {
                    return $query->whereNull('deleted_at');
                })
            ];
        }

        $baseRules['grade_level_id'] = 'required|integer|exists:grade_levels,id';

        return $baseRules;
    }

    public function messages()
    {
        return [
            'grade_level_id.required' => 'The grade level field is required.',
            'grade_level_id.integer' => 'The grade level must be an integer.',
            'grade_level_id.exists' => 'The selected grade level is invalid.',
        ];
    }
}
