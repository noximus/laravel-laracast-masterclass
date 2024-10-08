<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class ReplaceTicketRequest extends FormRequest
{
    // Determine if the user is authorized to make this request.
    public function authorize(): bool
    {
        return true;
    }
    // Get the validation rules that apply to the request.
    public function rules(): array
    {
        $rules = [
            'data.attributes.title' => 'required|string',
            'data.attributes.description' => 'required|string',
            'data.attributes.status' => 'required|string|in:A,C,H,X',
            'data.relationships.author.data.id' => 'required|integer'
        ];

        return $rules;
    }
    // Get the validation error messages that apply to the request.
    public function messages(): array
    {
        return [
            'data.attributes.status' => 'The status field must be one of the following types: A, C, H, X.'
        ];
    }
}
