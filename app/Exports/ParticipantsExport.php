<?php

namespace App\Exports;

use App\Models\Workshop;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ParticipantsExport implements FromCollection, WithHeadings
{
    public function __construct(protected Workshop $workshop)
    {
    }

    public function headings(): array
    {
        return [
            'Name', 'Email', 'Mobile', 'WhatsApp', 'College', 'Course', 'Branch', 'Year',
            'Enrollment/Roll Number', 'Home State', 'Home District', 'Workshop', 'Registration Date',
            'Certificate No.', 'Feedback Submitted At',
            'Rate this Seminar Workshop', 'Previous Quaere Training', 'Interested in Quaere Trainee Role',
            'Preferred Program', 'Liked Practical Oriented Workshop', 'Learned New Industry Relevant Topics',
            "Today's Speaker", 'Speaker Rating', 'Speaker Answered Questions', 'Valuable Comments',
        ];
    }

    public function collection(): Collection
    {
        return $this->workshop->participants()->with('certificate')->orderByDesc('registered_at')->get()->map(fn ($p) => [
            $p->name, $p->email, $p->mobile, $p->whatsapp_number, $p->college_name,
            $p->course, $p->branch, $p->year, $p->enrollment_number, $p->home_state,
            $p->home_district, $this->workshop->title, $p->registered_at?->format('Y-m-d H:i'),
            $p->certificate?->certificate_id ?? '',
            $p->feedback_submitted_at?->format('Y-m-d H:i'),
            $p->feedback_workshop_rating, $p->feedback_previous_quaere_training, $p->feedback_trainee_interest,
            $p->feedback_program_interest, $p->feedback_practical_oriented, $p->feedback_industry_relevant,
            $p->feedback_speaker, $p->feedback_speaker_rating, $p->feedback_speaker_answered_questions,
            $p->feedback_comments,
        ]);
    }
}
