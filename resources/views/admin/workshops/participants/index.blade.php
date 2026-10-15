@extends('layouts.admin')

@section('title', 'Participants — ' . $workshop->title)

@section('content')
@push('styles')
<style>
    .participants-hero { background:linear-gradient(135deg,#005495,#003b68); color:#fff; border-radius:1rem; padding:1.1rem 1.25rem; }
    .participants-hero .logo { max-height:42px; max-width:180px; background:#fff; border-radius:.5rem; padding:.25rem .45rem; object-fit:contain; }
    .participant-table th { white-space:nowrap; }
    .participant-table td { vertical-align:middle; }
</style>
@endpush

<div class="participants-hero mb-4 d-flex justify-content-between align-items-center gap-3">
    <div>
        <a href="{{ route('admin.workshops.show', $workshop) }}" class="text-white-50 text-decoration-none small"><i class="bi bi-arrow-left me-1"></i>{{ $workshop->title }}</a>
        <h1 class="h4 mb-1 mt-1">Participants</h1>
        <div class="small text-white-50">{{ $workshop->college_name }} &middot; {{ $workshop->duration_label }}</div>
    </div>
    <img class="logo" src="{{ asset('images/qwaretech-hub-logo.png') }}" alt="Quaere Tech Hub" onerror="this.style.display='none'">
</div>

<div class="card stat-card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-lg-5">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search name, email, mobile, WhatsApp or enrollment..."><!-- all searchable fields are server-side -->
            </div>
            <div class="col-lg-2"><button type="submit" class="btn btn-outline-primary w-100"><i class="bi bi-funnel me-1"></i> Search</button></div>
        </form>
    </div>
</div>

<div class="card stat-card">
    <div class="table-responsive">
        <table class="table participant-table align-middle mb-0">
            <thead class="text-muted small text-uppercase">
                <tr>
                    <th style="width:40px;">#</th>
                    <th>Name</th><th>Enrollment ID</th><th>Email ID</th><th>Mobile</th><th>WhatsApp</th><th>Course / Branch</th><th>Home District</th><th>Certificate ID</th><th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($participants as $participant)
                    <tr>
                        <td>{{ $participants->firstItem() + $loop->index }}</td>
                        <td class="fw-semibold">{{ $participant->name }}</td>
                        <td><code>{{ $participant->enrollment_id ?? '—' }}</code></td>
                        <td>{{ $participant->email }}</td>
                        <td>{{ $participant->mobile }}</td>
                        <td>{{ $participant->whatsapp_number ?? '—' }}</td>
                        <td>{{ $participant->course }} &middot; {{ $participant->branch }}</td>
                        <td>{{ $participant->home_district ?? '—' }}</td>
                        <td><code>{{ $participant->certificate?->certificate_id ?? '—' }}</code></td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-1">
                                @if ($participant->certificate?->status === 'generated')
                                    @if ($workshop->certificatesAvailableForDownload())
                                        <a href="{{ route('admin.workshops.participants.certificate.download', [$workshop, $participant]) }}" class="btn btn-outline-primary btn-sm" title="Download Certificate"><i class="bi bi-download"></i></a>
                                    @else
                                        <button type="button" class="btn btn-outline-secondary btn-sm" disabled title="Available from {{ $workshop->start_date?->format('d M Y') }}"><i class="bi bi-lock"></i></button>
                                    @endif
                                @elseif ($workshop->certificateTemplate && $workshop->certificateTemplate->status)
                                    <form method="POST" action="{{ route('admin.workshops.participants.certificate.generate', [$workshop, $participant]) }}">@csrf<button type="submit" class="btn btn-outline-success btn-sm">Create Certificate</button></form>
                                @else
                                    <span class="text-muted small">Template required</span>
                                @endif
                                <form method="POST" action="{{ route('admin.workshops.participants.destroy', [$workshop, $participant]) }}" onsubmit="return confirm('Remove this participant?');">@csrf @method('DELETE')<button type="submit" class="btn btn-outline-danger btn-sm" title="Remove"><i class="bi bi-trash"></i></button></form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="10" class="p-0">@include('partials.empty-state', ['icon' => 'bi-people', 'message' => 'No participants match your filters.'])</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white">{{ $participants->links() }}</div>
</div>
@endsection

