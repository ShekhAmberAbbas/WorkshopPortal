@extends('layouts.admin')

@section('title', $profile->name)

@section('content')

<div class="d-flex align-items-center justify-content-between gap-2 mb-4">

    <div>
        <a
            href="{{ route('admin.participants.index') }}"
            class="text-decoration-none text-muted small"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back to Unique Students
        </a>

        <h1 class="h3 mt-1 mb-0">
            {{ $profile->name }}
        </h1>

        <div class="small text-muted mt-1">
            Unique student profile and complete workshop history
        </div>
    </div>

    {{-- Export student profile --}}
    <form
        method="POST"
        action="{{ route('admin.participants.export') }}"
        data-no-loading="true"
        class="m-0"
    >
        @csrf

        <input
            type="hidden"
            name="export_mode"
            value="unique"
        >

        <input
            type="hidden"
            name="replace_filters"
            value="1"
        >

        <input
            type="hidden"
            name="search"
            value="{{ $profile->email ?: $profile->mobile }}"
        >

        <button
            type="submit"
            class="btn btn-outline-primary btn-sm"
        >
            <i class="bi bi-file-earmark-excel me-1"></i>
            Export
        </button>
    </form>

</div>


{{-- =========================================================
     STUDENT PROFILE
========================================================= --}}

<div class="card stat-card mb-4">

    <div class="card-body">

        <h2 class="h6 mb-3">
            <i class="bi bi-person-badge me-1 text-primary"></i>
            Student Profile
        </h2>


        <div class="row g-3 small">

            {{-- Name --}}
            <div class="col-md-6">
                <div class="text-muted">
                    Name
                </div>

                <div class="fw-semibold">
                    {{ $profile->name ?: '—' }}
                </div>
            </div>


            {{-- Email --}}
            <div class="col-md-6">
                <div class="text-muted">
                    Email ID
                </div>

                <div class="fw-semibold">
                    {{ $profile->email ?: '—' }}
                </div>
            </div>


            {{-- Mobile --}}
            <div class="col-md-6">
                <div class="text-muted">
                    Mobile
                </div>

                <div class="fw-semibold">
                    {{ $profile->mobile ?: '—' }}
                </div>
            </div>


            {{-- College --}}
            <div class="col-md-6">
                <div class="text-muted">
                    College
                </div>

                <div class="fw-semibold">
                    {{ $profile->college_name ?: '—' }}
                </div>
            </div>


            {{-- Course --}}
            <div class="col-md-4">
                <div class="text-muted">
                    Course
                </div>

                <div class="fw-semibold">
                    {{ $profile->course ?: '—' }}
                </div>
            </div>


            {{-- Branch --}}
            <div class="col-md-4">
                <div class="text-muted">
                    Branch
                </div>

                <div class="fw-semibold">
                    {{ $profile->branch ?: '—' }}
                </div>
            </div>


            {{-- Year --}}
            <div class="col-md-4">
                <div class="text-muted">
                    Year
                </div>

                <div class="fw-semibold">
                    {{ $profile->year ?: '—' }}
                </div>
            </div>


            {{-- Enrollment --}}
            <div class="col-md-4">
                <div class="text-muted">
                    Enrollment / Roll Number
                </div>

                <div class="fw-semibold">
                    {{ $profile->enrollment_number ?: '—' }}
                </div>
            </div>


            {{-- Home State --}}
            <div class="col-md-4">
                <div class="text-muted">
                    Home State
                </div>

                <div class="fw-semibold">
                    {{ $profile->home_state ?: '—' }}
                </div>
            </div>


            {{-- Home District --}}
            <div class="col-md-4">
                <div class="text-muted">
                    Home District
                </div>

                <div class="fw-semibold">
                    {{ $profile->home_district ?: '—' }}
                </div>
            </div>

        </div>


        <hr>


        {{-- =====================================================
             REGISTRATION SUMMARY
        ====================================================== --}}

        <div class="row g-3 small">

            {{-- Workshops --}}
            <div class="col-md-4">

                <div class="text-muted">
                    Workshops Attended
                </div>

                <div class="fs-5 fw-bold text-primary">
                    {{ $registrations->count() }}
                </div>

            </div>


            {{-- First Registration --}}
            <div class="col-md-4">

                <div class="text-muted">
                    First Registration
                </div>

                <div class="fw-semibold">

                    @if ($registrations->isNotEmpty() && $registrations->last()->registered_at)

                        {{ $registrations->last()->registered_at->format('d M Y') }}

                    @else

                        —

                    @endif

                </div>

            </div>


            {{-- Last Registration --}}
            <div class="col-md-4">

                <div class="text-muted">
                    Last Registration
                </div>

                <div class="fw-semibold">

                    @if ($registrations->isNotEmpty() && $registrations->first()->registered_at)

                        {{ $registrations->first()->registered_at->format('d M Y') }}

                    @else

                        —

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>



{{-- =========================================================
     WORKSHOP HISTORY
========================================================= --}}

<div class="card stat-card">

    <div class="card-header bg-white border-0 pt-3">

        <div class="d-flex align-items-center justify-content-between">

            <h2 class="h6 mb-0">
                <i class="bi bi-mortarboard me-1 text-primary"></i>
                Workshop History
            </h2>

            <span class="small text-muted">
                {{ $registrations->count() }}
                {{ $registrations->count() === 1 ? 'Workshop' : 'Workshops' }}
            </span>

        </div>

    </div>


    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="text-muted small text-uppercase">
                <tr>
                    <th>Workshop Topic</th>
                    <th>College</th>
                    <th>Code</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Duration</th>
                    <th>Registered</th>
<th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($registrations as $registration)
                @php
                    $workshop = $registration->workshop;
                    $startDate = $workshop?->start_date;
                    $endDate = $workshop?->computed_end_date;
                @endphp
                <tr>
                    <td>
                        @if($workshop)
                            <a href="{{ route('admin.workshops.show', $workshop) }}" class="fw-semibold text-decoration-none">{{ $workshop->title ?: '—' }}</a>
                        @else — @endif
                    </td>
                    <td>{{ $registration->college_name ?: '—' }}</td>
                    <td><code>{{ $workshop?->workshop_code ?: '—' }}</code></td>
                    <td>{{ $startDate?->format('d M Y') ?: '—' }}</td>
                    <td>{{ $endDate?->format('d M Y') ?: '—' }}</td>
                    <td><span class="badge bg-light text-dark border">{{ $workshop?->duration_label ?: '—' }}</span></td>
                    <td>{{ $registration->registered_at?->format('d M Y') ?: '—' }}</td>
                    <td class="text-end">
                        <a href="{{ route('admin.participants.show', $registration) }}" class="btn btn-outline-secondary btn-sm" title="View Registration"><i class="bi bi-eye"></i></a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-5">
                        <i class="bi bi-mortarboard d-block mb-2" style="font-size:2rem;"></i>
                        <div class="fw-semibold">No workshop registrations found.</div>
                        <div class="small">This student has no registration history.</div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

@endsection