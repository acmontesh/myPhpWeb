<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PublicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $publicationId = $this->route('publication')?->id;

        return [
            'doi' => ['required', 'string', 'max:150', Rule::unique('publications', 'doi')->ignore($publicationId)],
            'type' => ['required', 'in:journal,conference'],
            'title.en' => ['required', 'string', 'max:500'],
            'title.es' => ['nullable', 'string', 'max:500'],
            'abstract.en' => ['nullable', 'string'],
            'abstract.es' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'remove_image' => ['nullable', 'boolean'],
            // 50 MB — some papers are genuinely large. Note this only takes
            // effect if PHP's own post_max_size/upload_max_filesize allow it;
            // see the php.ini note in tasks.md.
            'pdf' => ['nullable', 'file', 'mimes:pdf', 'max:51200'],
            'remove_pdf' => ['nullable', 'boolean'],
            'video_url' => ['nullable', 'url', 'max:500'],
            'citation_count' => ['nullable', 'integer', 'min:0'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'pdf.max' => 'The PDF must not be larger than 50 MB.',
            'image.max' => 'The image must not be larger than 5 MB.',
        ];
    }
}
