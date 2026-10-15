<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWorkshopRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:191'],
            'description' => ['nullable', 'string', 'max:5000'],
            'college_name' => ['required', 'string', 'max:191'],
            'college_address' => ['nullable', 'string', 'max:1000'],
            'enrollment_prefix' => ['nullable', 'string', 'max:100', 'regex:/^[A-Za-z0-9_\/-]+$/'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i', 'after_or_equal:start_time'],
            'trainer_name' => ['required', 'string', 'max:150'],
            'trainer_designation' => ['nullable', 'string', 'max:150'],
            'max_participants' => ['nullable', 'integer', 'min:1', 'max:100000'],
            'status' => ['required', 'in:draft,active,inactive,completed'],
            'certificate_template_id' => [
                'required', 'integer',
                Rule::exists('certificate_templates', 'id')->where('status', true),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Please enter the workshop topic.',
            'college_name.required' => 'Please enter the college name.',
            'start_date.required' => 'Please select the start date.',
            'end_date.required' => 'Please select the end date.',
            'end_date.after_or_equal' => 'End date must be the same as or after the start date.',
            'trainer_name.required' => 'Please enter the trainer name.',
            'certificate_template_id.required' => 'Please select an active certificate template. Every successful registration receives a certificate.',
        ];
    }
}
