<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCertificateTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:191',
            // mimetypes checks actual file content, not just the extension —
            // mimes alone can be spoofed by renaming a file.
            'file' => [$this->isMethod('post') ? 'required' : 'nullable', 'file', 'mimes:pdf', 'mimetypes:application/pdf', 'max:10240'],
            'status' => 'boolean',
            'config' => 'array',
            'config.*.x' => 'nullable|numeric',
            'config.*.y' => 'nullable|numeric',
            'config.*.font_size' => 'nullable|numeric|min:6|max:120',
            'config.*.size' => 'nullable|numeric|min:20|max:400',
        ];
    }
}
