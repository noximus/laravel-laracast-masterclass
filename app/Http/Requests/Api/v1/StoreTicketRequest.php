<?php

namespace App\Http\Requests\Api\V1;

use App\Permissions\V1\Abilities;
use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends BaseTicketRequest
{
    // Determine if the user is authorized to make this request.
    public function authorize(): bool
    {
        return true;
    }
    // Validation rules that apply to the request.
    public function rules(): array
    {
        $authorIdAtrri = $this->routeIs('tickets.store') ? 'data.relationships.author.data.id' : 'author';
        $user = $this->user();
        $authorRule = 'required|integer:exists:users,id';
        $rules = [
            'data.attributes.title' => 'required|string',
            'data.attributes.description' => 'required|string',
            'data.attributes.status' => 'required|string|in:A,C,H,X',
            $authorIdAtrri => $authorRule . '|size:' . $user->id,
        ];

        $user = $this->user();

        if ($this->user()->tokenCan(Abilities::CreateTicket)) {
            $rules[$authorIdAtrri] = $authorRule;
        }

        return $rules;
    }
    protected function prepareForValidation()
    {
        if ($this->routeIs('authors.tickets.store')) {
            $this->merge([
                'author' => $this->route('author'),
            ]);
        }
    }
}
