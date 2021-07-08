<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookFilterRequest extends FormRequest
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
            'author_id'    => 'sometimes|numeric|exists:author,id',
            'subauthor_id' => 'sometimes|numeric|exists:author,id',
            'author_title' => 'sometimes|string|max:512',
            'book_title'   => 'sometimes|string|max:512',
            'released_at'  => 'sometimes|date'
        ];
    }
}
