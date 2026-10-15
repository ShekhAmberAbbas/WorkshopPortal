@extends('layouts.admin')

@section('title', 'Participant Management')

@push('styles')
<style>
    .participant-page .page-hero {
        background: linear-gradient(135deg, #005495, #003b68);
        color: #fff;
        border-radius: 1rem;
        padding: 1.25rem 1.35rem;
    }

    .participant-page .page-hero .logo {
        max-height: 46px;
        max-width: 190px;
        background: #fff;
        border-radius: .5rem;
        padding: .25rem .45rem;
        object-fit: contain;
    }

    .participant-table th {
        white-space: nowrap;
    }

    .participant-table td {
        vertical-align: middle;
    }

    .filter-label {
        font-size: .78rem;
        font-weight: 600;
        color: #5c6b77;
    }

    .stat-card {
        border: 0;
    }

    .student-name {
        color: #16324f;
    }

    .student-name:hover {
        color: #005495;
    }

    .table-wrap {
        overflow-x: auto;
    }

    .table-wrap table {
        min-width: 1500px;
    }

    .mode-switch .btn {
        white-space: nowrap;
    }

    .export-menu {
        min-width: 260px;
    }

    .participant-page .pagination {
        margin-bottom: 0;
    }

    .participant-page .pagination .page-link {
        min-width: 34px;
        height: 34px;
        padding: .35rem .55rem;
        font-size: .82rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .participant-page .pagination .page-item {
        margin: 0 1px;
    }

    .export-menu form {
        margin: 0;
    }

    .filter-chip {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        padding: .3rem .55rem;
        border-radius: 999px;
        background: #eef6fb;
        color: #005495;
        font-size: .75rem;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<div class="participant-page">

    <div class="page-hero mb-4 d-flex align-items-center justify-content-between gap-3">
        <div>
            <div class="small text-white-50 text-uppercase fw-semibold">Quaere Tech Hub</div>
            <h1 class="h3 mb-1">Participant Management</h1>
            <div class="small text-white-50">
                Manage registrations, unique students, workshops and certificates.
            </div>
        </div>

        <img
            class="logo"
            src="{{ asset('images/qwaretech-hub-logo.png') }}"
            alt="Quaere Tech Hub"
            onerror="this.style.display='none'"
        >
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="value">{{ number_format($counts['total_registrations']) }}</div>
                    <div class="text-muted small">Total Registrations</div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="value">{{ number_format($counts['unique_students']) }}</div>
                    <div class="text-muted small">Unique Students</div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="value">{{ number_format($counts['filtered']) }}</div>
                    <div class="text-muted small">
                        {{ $mode === 'unique' ? 'Filtered Students' : 'Filtered Registrations' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" id="filter-form">
        @csrf
        <input type="hidden" name="mode" value="{{ $mode }}">

        <div class="card stat-card mb-3">
            <div class="card-body">

                <div class="d-flex flex-wrap gap-2 align-items-center mb-3">

                    <div class="input-group flex-grow-1" style="min-width:260px;">
                        <span class="input-group-text bg-white">
                            <i class="bi bi-search"></i>
                        </span>

                        <input
                            type="search"
                            name="search"
                            value="{{ $filters['search'] ?? '' }}"
                            class="form-control"
                            placeholder="Search name, email, mobile, college, enrollment, workshop..."
                        >
                    </div>

                    <div class="btn-group mode-switch" role="group">
                        <input
                            type="radio"
                            class="btn-check"
                            name="mode_switch"
                            id="mode_all"
                            value="all"
                            @checked($mode === 'all')
                            onchange="changeMode('all')"
                        >
                        <label class="btn btn-outline-primary btn-sm" for="mode_all">
                            All Registrations
                        </label>

                        <input
                            type="radio"
                            class="btn-check"
                            name="mode_switch"
                            id="mode_unique"
                            value="unique"
                            @checked($mode === 'unique')
                            onchange="changeMode('unique')"
                        >
                        <label class="btn btn-outline-primary btn-sm" for="mode_unique">
                            Unique Students
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-search me-1"></i> Search
                    </button>

                    <a
                        href="{{ route('admin.participants.index', ['reset' => 1]) }}"
                        class="btn btn-outline-secondary btn-sm"
                    >
                        <i class="bi bi-arrow-counterclockwise me-1"></i>
                        Reset
                    </a>

                    <button
                        type="button"
                        class="btn btn-outline-secondary btn-sm"
                        data-bs-toggle="collapse"
                        data-bs-target="#advancedFilters"
                    >
                        <i class="bi bi-sliders me-1"></i>
                        Advanced Filters
                    </button>
                </div>

                @if(!empty($filters))
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="filter-chip">
                            <i class="bi bi-funnel-fill"></i>
                            Filters applied
                        </span>

                        @if($filters['college_name'] ?? '')
                            <span class="filter-chip">College</span>
                        @endif

                        @if($filters['home_state'] ?? '')
                            <span class="filter-chip">State</span>
                        @endif

                        @if($filters['home_district'] ?? '')
                            <span class="filter-chip">Home District</span>
                        @endif

                    </div>
                @endif

                <div
                    class="collapse {{ !empty($filters) ? 'show' : '' }}"
                    id="advancedFilters"
                >
                    <hr>

                    <div class="row g-3">

                        <div class="col-md-3">
                            <label class="form-label filter-label">Workshop</label>
                            <select name="workshop_id" class="form-select form-select-sm">
                                <option value="">All Workshops</option>
                                @foreach($filterOptions['workshops'] as $w)
                                    <option
                                        value="{{ $w->id }}"
                                        @selected((string) ($filters['workshop_id'] ?? '') === (string) $w->id)
                                    >
                                        {{ $w->title }} ({{ $w->workshop_code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label filter-label">Workshop Status</label>
                            <select name="workshop_status" class="form-select form-select-sm">
                                <option value="">All Status</option>
                                @foreach(['draft','active','inactive','completed'] as $status)
                                    <option
                                        value="{{ $status }}"
                                        @selected(($filters['workshop_status'] ?? '') === $status)
                                    >
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label filter-label">College</label>
                            <select name="college_name" class="form-select form-select-sm">
                                <option value="">All Colleges</option>
                                @foreach($filterOptions['colleges'] as $college)
                                    <option
                                        value="{{ $college }}"
                                        @selected(($filters['college_name'] ?? '') === $college)
                                    >
                                        {{ $college }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label filter-label">Home State</label>
                            <select name="home_state" class="form-select form-select-sm">
                                <option value="">All States</option>
                                @foreach($filterOptions['states'] as $state)
                                    <option
                                        value="{{ $state }}"
                                        @selected(($filters['home_state'] ?? '') === $state)
                                    >
                                        {{ $state }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label filter-label">Home District</label>
                            <input
                                type="text"
                                name="home_district"
                                value="{{ $filters['home_district'] ?? '' }}"
                                class="form-control form-control-sm"
                                placeholder="Search city"
                            >
                        </div>

                        <div class="col-md-3">
                            <label class="form-label filter-label">Course</label>
                            <select name="course" class="form-select form-select-sm">
                                <option value="">All Courses</option>
                                @foreach($filterOptions['courses'] as $course)
                                    <option
                                        value="{{ $course }}"
                                        @selected(($filters['course'] ?? '') === $course)
                                    >
                                        {{ $course }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label filter-label">Branch</label>
                            <input
                                type="text"
                                name="branch"
                                value="{{ $filters['branch'] ?? '' }}"
                                class="form-control form-control-sm"
                                placeholder="Search branch"
                            >
                        </div>

                        <div class="col-md-3">
                            <label class="form-label filter-label">Year / Semester</label>
                            <select name="year" class="form-select form-select-sm">
                                <option value="">All Years</option>
                                @foreach($filterOptions['years'] as $year)
                                    <option
                                        value="{{ $year }}"
                                        @selected(($filters['year'] ?? '') === $year)
                                    >
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label filter-label">Enrollment / Roll No.</label>
                            <input
                                type="text"
                                name="enrollment_number"
                                value="{{ $filters['enrollment_number'] ?? '' }}"
                                class="form-control form-control-sm"
                                placeholder="Search enrollment"
                            >
                        </div>

                        
                        <div class="col-md-3">
                            <label class="form-label filter-label">Registered From</label>
                            <input
                                type="date"
                                name="registered_from"
                                value="{{ $filters['registered_from'] ?? '' }}"
                                class="form-control form-control-sm"
                            >
                        </div>

                        <div class="col-md-3">
                            <label class="form-label filter-label">Registered To</label>
                            <input
                                type="date"
                                name="registered_to"
                                value="{{ $filters['registered_to'] ?? '' }}"
                                class="form-control form-control-sm"
                            >
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-funnel me-1"></i>
                            Apply Filters
                        </button>

                        <a
                            href="{{ route('admin.participants.index', ['reset' => 1]) }}"
                            class="btn btn-outline-secondary btn-sm"
                        >
                            Clear Advanced Filters
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap mb-2">

        <div class="small text-muted">
            Showing
            <strong>{{ $results->firstItem() ?? 0 }}</strong>
            –
            <strong>{{ $results->lastItem() ?? 0 }}</strong>
            of
            <strong>{{ number_format($results->total()) }}</strong>
        </div>

        <div class="dropdown">
            <button
                class="btn btn-outline-primary btn-sm dropdown-toggle"
                type="button"
                data-bs-toggle="dropdown"
            >
                <i class="bi bi-file-earmark-excel me-1"></i>
                Export Excel
            </button>

            <ul class="dropdown-menu dropdown-menu-end export-menu">

                <li>
                    <form method="POST" action="{{ route('admin.participants.export') }}" data-no-loading="true">
                        @csrf
                        <input type="hidden" name="export_mode" value="registrations">
                        <button type="submit" class="dropdown-item">
                            <i class="bi bi-list-ul me-2 text-primary"></i>
                            Export Current Registrations
                        </button>
                    </form>
                </li>

                <li>
                    <form method="POST" action="{{ route('admin.participants.export') }}" data-no-loading="true">
                        @csrf
                        <input type="hidden" name="export_mode" value="unique">
                        <button type="submit" class="dropdown-item">
                            <i class="bi bi-person-check me-2 text-success"></i>
                            Export Unique Students
                        </button>
                    </form>
                </li>

            </ul>
        </div>
    </div>

    <div class="card stat-card">

        <div class="table-wrap">

            @if($mode === 'all')

                <table class="table participant-table align-middle mb-0">

                    <thead class="text-muted small text-uppercase">
                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>College</th>
                        <th>Course / Branch / Year</th>
                        <th>Enrollment</th>
                        <th>Home</th>
                        <th>Workshop</th>
                        <th>Registered</th>
                        <th>Certificate ID</th>
                        <th class="text-end">Actions</th>
                    </tr>
                    </thead>

                    <tbody>

                    @forelse($results as $i => $participant)

                        @php
                            $identifier = base64_encode(strtolower(trim($participant->email ?: $participant->mobile ?: $participant->enrollment_number ?: $participant->name . '|' . $participant->college_name)));
                        @endphp

                        <tr>

                            <td>
                                {{ $results->firstItem() + $i }}
                            </td>

                            <td>
                                <a
                                    href="{{ route('admin.participants.show', $participant) }}"
                                    class="fw-semibold text-decoration-none student-name"
                                >
                                    {{ $participant->name }}
                                </a>
                            </td>

                            <td class="small">{{ $participant->email }}</td>
                            <td class="small">{{ $participant->mobile }}</td>
                            <td class="small">{{ $participant->college_name }}</td>

                            <td class="small">
                                {{ $participant->course ?: '—' }}
                                @if($participant->branch)
                                    <br><span class="text-muted">{{ $participant->branch }}</span>
                                @endif
                                @if($participant->year)
                                    <br><span class="text-muted">{{ $participant->year }}</span>
                                @endif
                            </td>

                            <td class="small">
                                {{ $participant->enrollment_number ?: '—' }}
                            </td>

                            <td class="small">
                                {{ trim(($participant->home_district ?: '') . ', ' . ($participant->home_state ?: ''), ', ') ?: '—' }}
                            </td>

                            <td class="small">
                                @if($participant->workshop)
                                    <a
                                        href="{{ route('admin.workshops.show', $participant->workshop) }}"
                                        class="text-decoration-none"
                                    >
                                        {{ $participant->workshop->title }}
                                    </a>

                                    <br>

                                    <code>{{ $participant->workshop->workshop_code }}</code>
                                @else
                                    —
                                @endif
                            </td>

                            <td class="small">
                                {{ $participant->registered_at?->format('d M Y') ?: '—' }}
                            </td>

                            <td><code>{{ $participant->certificate?->certificate_id ?? '—' }}</code></td>

                            <td class="text-end">

                                <div class="btn-group btn-group-sm">

                                    <a
                                        href="{{ route('admin.participants.show', $participant) }}"
                                        class="btn btn-outline-secondary"
                                        title="View Registration"
                                    >
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <a
                                        href="{{ route('admin.participants.student', $identifier) }}"
                                        class="btn btn-outline-secondary"
                                        title="View Student"
                                    >
                                        <i class="bi bi-person-lines-fill"></i>
                                    </a>

                                    @if($participant->certificate?->status === 'generated')
                                        @if($participant->workshop?->certificatesAvailableForDownload())
                                            <a
                                                href="{{ route('certificate.download', $participant->certificate) }}"
                                                class="btn btn-outline-primary"
                                                title="Download Certificate"
                                            >
                                                <i class="bi bi-download"></i>
                                            </a>
                                        @else
                                            <button type="button" class="btn btn-outline-secondary" disabled title="Available from {{ $participant->workshop?->start_date?->format('d M Y') }}">
                                                <i class="bi bi-lock"></i>
                                            </button>
                                        @endif
                                    @endif

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="12" class="p-0">
                                @include('partials.empty-state', [
                                    'icon' => 'bi-people',
                                    'message' => 'No participants found. Try changing or clearing your filters.'
                                ])
                            </td>
                        </tr>

                    @endforelse

                    </tbody>
                </table>

            @else

                <table class="table participant-table align-middle mb-0">

                    <thead class="text-muted small text-uppercase">
                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>College</th>
                        <th>Workshops</th>
                        <th>Last Workshop</th>
                        <th>First Registration</th>
                        <th>Last Registration</th>
                        <th class="text-end">Actions</th>
                    </tr>
                    </thead>

                    <tbody>

                    @forelse($results as $i => $participant)

                        @php
                            $identifier = base64_encode(strtolower(trim($participant->email ?: $participant->mobile ?: $participant->enrollment_number ?: $participant->name . '|' . $participant->college_name)));
                        @endphp

                        <tr>

                            <td>
                                {{ $results->firstItem() + $i }}
                            </td>

                            <td>
                                <a
                                    href="{{ route('admin.participants.student', $identifier) }}"
                                    class="fw-semibold text-decoration-none student-name"
                                >
                                    {{ $participant->name }}
                                </a>
                            </td>

                            <td class="small">{{ $participant->email }}</td>
                            <td class="small">{{ $participant->mobile }}</td>
                            <td class="small">{{ $participant->college_name }}</td>

                            <td>
                                <span class="badge text-bg-info">
                                    {{ $participant->total_workshops }}
                                </span>
                            </td>

                            <td class="small">
                                {{ $participant->workshop?->title ?: '—' }}
                            </td>

                            <td class="small">
                                {{ $participant->first_registration
                                    ? \Illuminate\Support\Carbon::parse($participant->first_registration)->format('d M Y')
                                    : '—' }}
                            </td>

                            <td class="small">
                                {{ $participant->last_registration
                                    ? \Illuminate\Support\Carbon::parse($participant->last_registration)->format('d M Y')
                                    : '—' }}
                            </td>

                            <td class="text-end">

                                <a
                                    href="{{ route('admin.participants.student', $identifier) }}"
                                    class="btn btn-outline-primary btn-sm"
                                >
                                    <i class="bi bi-person-lines-fill me-1"></i>
                                    View Student
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="10" class="p-0">
                                @include('partials.empty-state', [
                                    'icon' => 'bi-people',
                                    'message' => 'No unique students found. Try changing or clearing your filters.'
                                ])
                            </td>
                        </tr>

                    @endforelse

                    </tbody>
                </table>

            @endif

        </div>

        <div class="card-footer bg-white">
            {{ $results->links('pagination::bootstrap-5') }}
        </div>

    </div>
@endsection
