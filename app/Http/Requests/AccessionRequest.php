<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AccessionRequest extends FormRequest
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
            $baseRules['accession_no'] = [
                'required', 'string', 'alpha_dash', 'max:45', Rule::unique('accessions')->where(function ($query) {
                    return $query->whereNull('deleted_at');
                })
            ];
        }

        if($this->getMethod() === 'PUT' || $this->getMethod() === 'PATCH')
        {
            $baseRules['accession_no'] = [
                'required', 'string', 'alpha_dash', 'max:45', Rule::unique('accessions')->ignore($this->segment(2))->where(function ($query) {
                    return $query->whereNull('deleted_at');
                })
            ];
        }

        $baseRules['book_id'] = 'required|integer|exists:books,id';
        $baseRules['location_id'] = 'required|integer|exists:locations,id';
        $baseRules['acquired_date'] = 'required|date';
        $baseRules['donnor_name'] = 'required|string|max:100';
        $baseRules['price'] = 'required|numeric|gt:0';

        return $baseRules;
    }

    public function messages()
    {
        return [
            'book_id.required' => 'The book title field is required.',
            'book_id.integer' => 'The book title must be an integer.',
            'book_id.exists' => 'The selected book title is invalid.',

            'location_id.required' => 'The book shelf/location name field is required.',
            'location_id.integer' => 'The book shelf/location name must be an integer.',
            'location_id.exists' => 'The selected book shelf/location name is invalid.',
        ];
    }
}
