<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AllRegistrationsExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(protected Builder $query)
    {
    }

    public function query(): Builder
    {
        return $this->query->with(['workshop', 'certificate']);
    }

    public function headings(): array
    {
        return [
            'Student Name', 'Email ID', 'Mobile', 'WhatsApp', 'College', 'Course', 'Branch', 'Year',
            'Enrollment/Roll Number', 'Home State', 'Home District', 'Workshop Topic', 'Workshop Code',
            'Workshop Start Date', 'Workshop End Date', 'Duration', 'Registration Date', 'Certificate No.',
            'Feedback Submitted At',
            'Rate this Seminar Workshop', 'Previous Quaere Training', 'Interested in Quaere Trainee Role',
            'Preferred Program', 'Liked Practical Oriented Workshop', 'Learned New Industry Relevant Topics',
            "Today's Speaker", 'Speaker Rating', 'Speaker Answered Questions', 'Valuable Comments',
        ];
    }

    public function map($participant): array
    {
        $workshop = $participant->workshop;

        return [
            $participant->name,
            $participant->email,
            $participant->mobile,
            $participant->whatsapp_number,
            $participant->college_name,
            $participant->course,
            $participant->branch,
            $participant->year,
            $participant->enrollment_number,
            $participant->home_state,
            $participant->home_district,
            $workshop?->title,
            $workshop?->workshop_code,
            $workshop?->start_date?->format('Y-m-d'),
            $workshop?->computed_end_date?->format('Y-m-d'),
            $workshop?->duration_label,
            $participant->registered_at?->format('Y-m-d H:i'),
            $participant->certificate?->certificate_id ?? '',
            $participant->feedback_submitted_at?->format('Y-m-d H:i'),
            $participant->feedback_workshop_rating,
            $participant->feedback_previous_quaere_training,
            $participant->feedback_trainee_interest,
            $participant->feedback_program_interest,
            $participant->feedback_practical_oriented,
            $participant->feedback_industry_relevant,
            $participant->feedback_speaker,
            $participant->feedback_speaker_rating,
            $participant->feedback_speaker_answered_questions,
            $participant->feedback_comments,
        ];
    }
}
