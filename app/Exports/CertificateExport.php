<?php

namespace App\Exports;

use App\Models\Workshop;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CertificateExport implements FromCollection, WithHeadings
{
    public function __construct(protected Workshop $workshop)
    {
    }

    public function headings(): array
    {
        return ['Certificate ID', 'Participant', 'Workshop', 'Issue Date', 'Status'];
    }

    public function collection(): Collection
    {
        return $this->workshop->certificates()->with('participant')->get()->map(fn ($c) => [
            $c->certificate_id,
            $c->participant->name,
            $this->workshop->title,
            $c->issue_date->format('Y-m-d'),
            ucfirst($c->status),
        ]);
    }
}
