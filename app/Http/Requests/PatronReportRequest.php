<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatronReportRequest extends FormRequest
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
            'from' => 'required|date',
            'to' => 'required|date|gte:from',
            'patron_type_id' => 'required|integer|exists:patron_types,id',
        ];
    }

    public function messages()
    {
        return [
            'patron_type_id.required' => 'The patron type field is required.',
            'patron_type_id.integer' => 'The patron type must be an integer.',
            'patron_type_id.exists' => 'The selected patron type is invalid.',
        ];
    }
}
