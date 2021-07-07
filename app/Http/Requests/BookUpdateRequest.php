<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookUpdateRequest extends FormRequest
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
            'name'        => 'sometimes|required|string|max:256',
            'description' => 'sometimes|required|string|max:512',
            'author_id'   => 'sometimes|required|numeric|exists:author,id',
            'released_at' => 'sometimes|required|date'        
        ];
    }
}
