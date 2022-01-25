<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookRequest extends FormRequest
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
            $baseRules['title'] = [
                'required', 'string', 'max:100', Rule::unique('books')->where(function ($query) {
                    return $query->whereNull('deleted_at');
                })
            ];
        }

        if($this->getMethod() === 'PUT' || $this->getMethod() === 'PATCH')
        {
            $baseRules['title'] = [
                'required', 'string', 'max:100', Rule::unique('books')->ignore($this->segment(2))->where(function ($query) {
                    return $query->whereNull('deleted_at');
                })
            ];
        }

        $baseRules['call_number'] = 'required|string|max:100';
        $baseRules['isbn'] = 'required|string|max:100';

        $baseRules['edition'] = 'required|string|max:100';
        $baseRules['volume'] = 'required|integer|gt:0';

        $baseRules['publisher'] = 'required|string|max:100';
        $baseRules['place_publication'] = 'required|string|max:100';

        $baseRules['copy_right'] = 'required|string|max:100';
        $baseRules['copy'] = 'required|integer|gt:0';

        return $baseRules;
    }
}
