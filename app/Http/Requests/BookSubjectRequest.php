<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookSubjectRequest extends FormRequest
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
            'book_id' => 'required|integer|exists:books,id',
            'subject_id' => 'required|integer|exists:subjects,id',
        ];
    }

    public function messages()
    {
        return [
            'book_id.required' => 'The book title field is required.',
            'book_id.integer' => 'The book title must be an integer.',
            'book_id.exists' => 'The selected book title is invalid.',

            'subject_id.required' => 'The subject name field is required.',
            'subject_id.integer' => 'The subject name must be an integer.',
            'subject_id.exists' => 'The selected subject name is invalid.',
        ];
    }
}
