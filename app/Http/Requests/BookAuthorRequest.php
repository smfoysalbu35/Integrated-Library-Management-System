<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookAuthorRequest extends FormRequest
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
            'author_id' => 'required|integer|exists:authors,id',
            'book_id' => 'required|integer|exists:books,id',
        ];
    }

    public function messages()
    {
        return [
            'author_id.required' => 'The author name field is required.',
            'author_id.integer' => 'The author name must be an integer.',
            'author_id.exists' => 'The selected author name is invalid.',

            'book_id.required' => 'The book title field is required.',
            'book_id.integer' => 'The book title must be an integer.',
            'book_id.exists' => 'The selected book title is invalid.',
        ];
    }
}
