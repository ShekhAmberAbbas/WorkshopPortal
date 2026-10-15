@extends('layouts.admin')

@section('title', $participant->name)

@section('content')
    <div class="d-flex align-items-center justify-content-between gap-2 mb-4">

        <div>
            <a
                href="{{ route('admin.participants.index') }}"
                class="text-decoration-none text-muted small"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Back to Participants
            </a>

            <h1 class="h3 mt-1 mb-0">{{ $participant->name }}</h1>

            <div class="small text-muted mt-1">
                Registration details
            </div>
        </div>

        @if($participant->certificate?->status === 'generated' && $participant->workshop?->certificatesAvailableForDownload())
            <a
                href="{{ route('certificate.download', $participant->certificate) }}"
                class="btn btn-primary btn-sm"
            >
                <i class="bi bi-download me-1"></i>
                Download Certificate
            </a>
        @endif

    </div>

    <div class="row g-3">

        <div class="col-lg-8">

            <div class="card stat-card mb-3">
                <div class="card-body">

                    <h2 class="h6 mb-3">
                        <i class="bi bi-person me-1 text-primary"></i>
                        Student Information
                    </h2>

                    <dl class="row mb-0 small">

                        <dt class="col-sm-3">Name</dt>
                        <dd class="col-sm-9">{{ $participant->name }}</dd>

                        <dt class="col-sm-3">Email ID</dt>
                        <dd class="col-sm-9">{{ $participant->email ?: '—' }}</dd>

                        <dt class="col-sm-3">Mobile</dt>
                        <dd class="col-sm-9">{{ $participant->mobile ?: '—' }}</dd>


                    </dl>

                </div>
            </div>

            <div class="card stat-card mb-3">
                <div class="card-body">

                    <h2 class="h6 mb-3">
                        <i class="bi bi-mortarboard me-1 text-primary"></i>
                        Academic Information
                    </h2>

                    <dl class="row mb-0 small">

                        <dt class="col-sm-3">College</dt>
                        <dd class="col-sm-9">{{ $participant->college_name ?: '—' }}</dd>

                        <dt class="col-sm-3">Course</dt>
                        <dd class="col-sm-9">{{ $participant->course ?: '—' }}</dd>

                        <dt class="col-sm-3">Branch</dt>
                        <dd class="col-sm-9">{{ $participant->branch ?: '—' }}</dd>

                        <dt class="col-sm-3">Year</dt>
                        <dd class="col-sm-9">{{ $participant->year ?: '—' }}</dd>

                        <dt class="col-sm-3">Enrollment / Roll No.</dt>
                        <dd class="col-sm-9">{{ $participant->enrollment_number ?: '—' }}</dd>

                    </dl>

                </div>
            </div>

            <div class="card stat-card mb-3">
                <div class="card-body">

                    <h2 class="h6 mb-3">
                        <i class="bi bi-geo-alt me-1 text-primary"></i>
                        Home Location
                    </h2>

                    <dl class="row mb-0 small">

                        <dt class="col-sm-3">State</dt>
                        <dd class="col-sm-9">{{ $participant->home_state ?: '—' }}</dd>


                    </dl>

                </div>
            </div>

            <div class="card stat-card mb-3">
                <div class="card-body">

                    <h2 class="h6 mb-3">
                        <i class="bi bi-mortarboard-fill me-1 text-primary"></i>
                        Workshop Registration
                    </h2>

                    <dl class="row mb-0 small">

                        <dt class="col-sm-3">Workshop</dt>
                        <dd class="col-sm-9">
                            @if($participant->workshop)
                                <a
                                    href="{{ route('admin.workshops.show', $participant->workshop) }}"
                                    class="text-decoration-none"
                                >
                                    {{ $participant->workshop->title }}
                                </a>
                            @else
                                —
                            @endif
                        </dd>

                        <dt class="col-sm-3">Workshop Code</dt>
                        <dd class="col-sm-9">
                            <code>{{ $participant->workshop?->workshop_code ?: '—' }}</code>
                        </dd>

                        <dt class="col-sm-3">Start Date</dt>
                        <dd class="col-sm-9">
                            {{ $participant->workshop?->start_date?->format('d M Y') ?: '—' }}
                        </dd>

                        <dt class="col-sm-3">End Date</dt>
                        <dd class="col-sm-9">
                            {{ $participant->workshop?->computed_end_date?->format('d M Y') ?: '—' }}
                        </dd>

                        <dt class="col-sm-3">Duration</dt>
                        <dd class="col-sm-9">
                            {{ $participant->workshop?->duration_label ?: '—' }}
                        </dd>

                        <dt class="col-sm-3">Registered On</dt>
                        <dd class="col-sm-9">
                            {{ $participant->registered_at?->format('d M Y, h:i A') ?: '—' }}
                        </dd>

                    </dl>

                </div>
            </div>

            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h2 class="h6 mb-0"><i class="bi bi-chat-square-text me-1 text-primary"></i> Workshop Feedback</h2>
                        <span class="badge text-bg-{{ $participant->feedback_submitted_at ? 'success' : 'secondary' }}">{{ $participant->feedback_submitted_at ? 'Submitted' : 'Not Provided' }}</span>
                    </div>
                    <div class="row g-3 small">
                        @php
                            $feedbackRows = [
                                'Rate this Seminar Workshop' => $participant->feedback_workshop_rating,
                                'Previous Quaere Training' => $participant->feedback_previous_quaere_training,
                                'Interested in Quaere as Trainee' => $participant->feedback_trainee_interest,
                                'Preferred Program' => $participant->feedback_program_interest,
                                'Liked Practical Oriented Workshop' => $participant->feedback_practical_oriented,
                                'Learned Industry Relevant Topics' => $participant->feedback_industry_relevant,
                                "Today's Speaker" => $participant->feedback_speaker,
                                'Speaker Rating' => $participant->feedback_speaker_rating,
                                'Speaker Answered Questions' => $participant->feedback_speaker_answered_questions,
                            ];
                        @endphp
                        @foreach($feedbackRows as $label => $value)
                            <div class="col-md-6"><div class="text-muted">{{ $label }}</div><div class="fw-semibold mt-1">{{ $value ?: '—' }}</div></div>
                        @endforeach
                        <div class="col-12"><div class="text-muted">Valuable Comments</div><div class="border rounded-3 p-3 mt-1 bg-light-subtle">{{ $participant->feedback_comments ?: '—' }}</div></div>
                    </div>
                    @if($participant->feedback_submitted_at)
                        <div class="small text-muted mt-3">Submitted {{ $participant->feedback_submitted_at->format('d M Y, h:i A') }}</div>
                    @endif
                </div>
            </div>

        </div>

        <div class="col-lg-4">

            <div class="card stat-card mb-3">
                <div class="card-body">

                    <h2 class="h6 mb-3">
                        <i class="bi bi-patch-check me-1 text-primary"></i>
                        Certificate
                    </h2>

                    @if($participant->certificate?->status === 'generated')
                        <div class="small text-muted mt-2">Certificate ID</div>
                        <code>{{ $participant->certificate->certificate_id }}</code>

                        @if($participant->workshop?->certificatesAvailableForDownload())
                            <a href="{{ route('certificate.download', $participant->certificate) }}" class="btn btn-outline-primary btn-sm w-100 mt-3">
                                <i class="bi bi-download me-1"></i> Download Certificate
                            </a>
                        @else
                            <div class="small text-muted mt-3">
                                Available from {{ $participant->workshop?->start_date?->format('d M Y') }}.
                            </div>
                        @endif
                    @else
                        <div class="small text-muted">Certificate details are unavailable. Please contact the workshop administrator.</div>
                    @endif

                </div>
            </div>

            <div class="card stat-card">
                <div class="card-body">

                    <h2 class="h6 mb-3">
                        <i class="bi bi-info-circle me-1 text-primary"></i>
                        Registration Meta
                    </h2>

                    <div class="small">

                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted">Participant ID</span>
                            <strong>#{{ $participant->id }}</strong>
                        </div>

                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted">Registered</span>
                            <strong>
                                {{ $participant->registered_at?->format('d M Y') ?: '—' }}
                            </strong>
                        </div>

                        <div class="d-flex justify-content-between py-2">
                            <span class="text-muted">Enrollment ID</span>
                            <strong>
                                {{ $participant->enrollment_id ?: '—' }}
                            </strong>
                        </div>

                    </div>

                </div>
            </div>

        </div>

    </div>
@endsection
