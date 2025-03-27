<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientSearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'required_without:card_number', 'prohibits:card_number', 'string'],
            'card_number' => ['nullable', 'required_without:name', 'prohibits:name', 'alpha_num'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required_without' => 'Legalább az egyik mező kitöltése kötelező.',
            'card_number.required_without' => 'Legalább az egyik mező kitöltése kötelező.',
            'name.prohibits' => 'Csak az egyik mezőt töltheted ki egyszerre.',
            'card_number.prohibits' => 'Csak az egyik mezőt töltheted ki egyszerre.',
            'card_number.alpha_num' => 'A második mező csak betűket és számokat tartalmazhat.',
        ];
    }
}
