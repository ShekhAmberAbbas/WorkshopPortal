<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UniqueStudentsExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(protected Builder $uniqueQuery)
    {
    }

    public function query(): Builder
    {
        return $this->uniqueQuery->with(['workshop', 'certificate']);
    }

    public function headings(): array
    {
        return [
            'Student Name', 'Email ID', 'Mobile', 'WhatsApp', 'College', 'Course', 'Branch', 'Year',
            'Enrollment/Roll Number', 'Home State', 'Home District', 'Total Workshops',
            'First Registration', 'Last Registration', 'Latest Workshop', 'Latest Certificate No.',
            'Latest Feedback Submitted At', 'Latest Workshop Rating', 'Previous Quaere Training',
            'Trainee Interest', 'Preferred Program', 'Practical Workshop', 'Industry Relevant Learning',
            "Today's Speaker", 'Speaker Rating', 'Speaker Answered Questions', 'Valuable Comments',
        ];
    }

    public function map($participant): array
    {
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
            (int) $participant->total_workshops,
            $this->formatDate($participant->first_registration),
            $this->formatDate($participant->last_registration),
            $participant->workshop?->title ?? '—',
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

    private function formatDate($value): string
    {
        return $value ? Carbon::parse($value)->format('Y-m-d') : '—';
    }
}
