<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GradeLevelRequest extends FormRequest
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
            $baseRules['grade_level'] = [
                'required', 'integer', 'gt:0', Rule::unique('grade_levels')->where(function ($query) {
                    return $query->whereNull('deleted_at');
                })
            ];
        }

        if($this->getMethod() === 'PUT' || $this->getMethod() === 'PATCH')
        {
            $baseRules['grade_level'] = [
                'required', 'integer', 'gt:0', Rule::unique('grade_levels')->ignore($this->segment(2))->where(function ($query) {
                    return $query->whereNull('deleted_at');
                })
            ];
        }

        return $baseRules;
    }
}
