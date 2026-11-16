<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PublicPhraseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $phraseId = $this->route('phrase')?->id;

        return [
            'key' => ['required', 'string', 'max:150', Rule::unique('public_phrases', 'key')->ignore($phraseId)],
            'group' => ['nullable', 'string', 'max:100'],
            'value.en' => ['required', 'string', 'max:1000'],
            'value.es' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
