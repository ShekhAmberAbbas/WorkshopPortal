<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use App\Models\Workshop;

class StoreParticipantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $workshop = $this->route('workshop');
        if ($workshop instanceof Workshop && ($message = $workshop->registrationUnavailableMessage())) {
            $isEarlyVisit = $workshop->status === 'active' && $workshop->isBeforeStartDate();
            $status = $isEarlyVisit ? 200 : ($workshop->status === 'active' ? 503 : 404);

            throw new HttpResponseException(response()->view(
                'public.workshop.unavailable',
                ['workshop' => $workshop, 'availabilityMessage' => $message],
                $status
            ));
        }

        if ($this->filled('name')) {
            $this->merge(['name' => mb_strtoupper(trim((string) $this->input('name')), 'UTF-8')]);
        }

        if (! $this->filled('whatsapp_number') && $this->filled('whatsapp')) {
            $this->merge(['whatsapp_number' => $this->input('whatsapp')]);
        }
    }

    public function rules(): array
    {
        $workshop = $this->route('workshop');
        $feedback = config('workshop_feedback.questions', []);

        $rules = [
            'name' => ['required', 'string', 'max:191'],
            'email' => [
                'required', 'email', 'max:191',
                Rule::unique('participants', 'email')
                    ->where(fn ($query) => $query->where('workshop_id', $workshop->id)),
            ],
            'mobile' => [
                'required', 'string', 'regex:/^[6-9][0-9]{9}$/',
                Rule::unique('participants', 'mobile')
                    ->where(fn ($query) => $query->where('workshop_id', $workshop->id)),
            ],
            'whatsapp_number' => ['required', 'string', 'regex:/^[6-9][0-9]{9}$/'],
            'home_state' => ['required', 'string', 'max:100'],
            'home_district' => ['required', 'string', 'max:100'],
            'course_id' => [
                'required', 'integer', Rule::exists('courses', 'id')->where(fn ($q) => $q->where('status', true)),
            ],
            'branch_id' => [
                'required', 'integer',
                Rule::exists('branches', 'id')->where(fn ($q) =>
                    $q->where('status', true)->where('course_id', (int) $this->input('course_id'))
                ),
            ],
            'year' => ['required', 'string', 'max:50'],
            'enrollment_number' => ['required', 'string', 'max:50'],
        ];

        foreach ($feedback as $question) {
            $key = 'feedback_' . $question['key'];
            $rules[$key] = match ($question['type']) {
                'select', 'choice' => ['required', 'string', Rule::in($question['options'])],
                'textarea' => ['required', 'string', 'max:2000'],
                default => ['required', 'string', 'max:2000'],
            };
        }

        return $rules;
    }

    public function messages(): array
    {
        $messages = [
            'email.unique' => 'This email is already registered for this workshop.',
            'mobile.unique' => 'This mobile number is already registered for this workshop.',
            'mobile.regex' => 'Enter a valid 10-digit Indian mobile number.',
            'whatsapp_number.required' => 'Please enter your WhatsApp number.',
            'whatsapp_number.regex' => 'Enter a valid 10-digit Indian WhatsApp number.',
            'home_state.required' => 'Please select your home state.',
            'home_district.required' => 'Please select your home district.',
            'course_id.required' => 'Please select your course/program.',
            'course_id.exists' => 'The selected course is no longer available. Please choose another course.',
            'branch_id.required' => 'Please select your branch/specialization.',
            'branch_id.exists' => 'The selected branch is not available for this course.',
        ];

        foreach (config('workshop_feedback.questions', []) as $question) {
            $messages['feedback_' . $question['key'] . '.required'] =
                'Please answer: ' . $question['label'] . '.';
        }

        return $messages;
    }
}
