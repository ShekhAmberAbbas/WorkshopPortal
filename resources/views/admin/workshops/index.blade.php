@extends('layouts.admin')

@section('title', 'Workshops')

@section('content')
@push('styles')
<style>
    .workshop-page .page-hero { background:linear-gradient(135deg,#005495,#003b68); color:#fff; border-radius:1rem; padding:1.25rem 1.35rem; }
    .workshop-page .page-hero .logo { max-height:46px; max-width:190px; background:#fff; border-radius:.5rem; padding:.25rem .45rem; object-fit:contain; }
    .workshop-table th { white-space:nowrap; }
.topic-link {
    color: #16324f;
    transition: all .18s ease;
}

.topic-link:hover {
    color: #005495;
    text-decoration: underline !important;
    text-underline-offset: 3px;
}

.topic-link i {
    transition: transform .18s ease, opacity .18s ease;
}

.topic-link:hover i {
    opacity: 1 !important;
    transform: translate(2px, -2px);
}
    .status-select { min-width:118px; border-radius:.55rem; }
    .date-range { min-width:145px; }
</style>
@endpush

<div class="workshop-page">
    <div class="page-hero mb-4 d-flex align-items-center justify-content-between gap-3 flex-wrap">
        <div>
            <div class="small text-white-50 text-uppercase fw-semibold">Quaere Tech Hub</div>
            <h1 class="h3 mb-1">Workshops</h1>
            <div class="small text-white-50">Manage workshop topics, schedules, registrations and certificates.</div>
        </div>
        <div class="d-flex align-items-center gap-3 flex-shrink-0">
    <img class="logo"
         src="{{ asset('images/qwaretech-hub-logo.png') }}"
         alt="Quaere Tech Hub"
         onerror="this.style.display='none'">

    <a href="{{ route('admin.workshops.create') }}"
       class="btn btn-light fw-semibold shadow-sm text-nowrap">
        <i class="bi bi-plus-lg me-1"></i>
        Add New Workshop
    </a>
</div>   
 </div>


    <div class="card stat-card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-center">
                <div class="col-lg-6">
                    <label class="visually-hidden" for="workshop-search">Search</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input id="workshop-search" type="text" name="search" value="{{ request('search') }}"
                               class="form-control" placeholder="Search topic, college, or workshop code...">
                    </div>
                </div>
                <div class="col-lg-3">
                    <label class="visually-hidden" for="status-filter">Status</label>
                    <select id="status-filter" name="status" class="form-select">
                        <option value="">All Status</option>
                        @foreach (['draft', 'active', 'inactive', 'completed'] as $status)
                            <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-1">
                    <input type="hidden" name="sort" value="start_date">
                    <input type="hidden" name="direction" value="desc">
                </div>
                <div class="col-lg-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill"><i class="bi bi-funnel me-1"></i> Filter</button>
                    <a href="{{ route('admin.workshops.index') }}" class="btn btn-outline-secondary" title="Reset"><i class="bi bi-arrow-counterclockwise"></i></a>
                </div>
            </form>
        </div>
    </div>

    <div class="card stat-card">
        <div class="table-responsive">
            <table class="table workshop-table align-middle mb-0">
                <thead class="text-muted small text-uppercase">
                    <tr>
                        <th>Workshop Topic</th>
                        <th>Code</th>
                        <th>College</th>
                        <th>Schedule</th>
                        <th>Duration</th>
                        <th>Participants</th>
                        <th>Certificates</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($workshops as $workshop)
                        <tr>
                            <td>
    <a href="{{ route('admin.workshops.show', $workshop) }}"
       class="fw-semibold text-decoration-none topic-link d-inline-flex align-items-center gap-1"
       title="View Workshop">
        {{ $workshop->title }}
        <i class="bi bi-box-arrow-up-right small opacity-50"></i>
    </a>
</td>
                            <td><code>{{ $workshop->workshop_code }}</code></td>
                            <td>{{ $workshop->college_name }}</td>
                            <td class="date-range">
                                <div class="fw-semibold">{{ $workshop->start_date?->format('d M Y') ?: '—' }}</div>
                                <div class="small text-muted">to {{ $workshop->end_date?->format('d M Y') ?: '—' }}</div>
                            </td>
                            <td><span class="badge rounded-pill text-bg-light border">{{ $workshop->duration_label }}</span></td>
                            <td>{{ $workshop->participants_count }}</td>
                            <td>{{ $workshop->certificates_count }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.workshops.toggle-status', $workshop) }}">
                                    @csrf
                                    <select name="status" class="form-select form-select-sm status-select" onchange="this.form.submit()" aria-label="Workshop status">
                                        @foreach (['draft', 'active', 'inactive', 'completed'] as $status)
                                            <option value="{{ $status }}" @selected($workshop->status === $status)>{{ ucfirst($status) }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                            <td class="text-end">
    <div class="btn-group btn-group-sm">

        {{-- View --}}
        <a href="{{ route('admin.workshops.show', $workshop) }}"
           class="btn btn-outline-primary"
           title="View Workshop"
           aria-label="View Workshop">
            <i class="bi bi-eye"></i>
        </a>

        {{-- Edit --}}
        <a href="{{ route('admin.workshops.edit', $workshop) }}"
           class="btn btn-outline-secondary"
           title="Edit Workshop"
           aria-label="Edit Workshop">
            <i class="bi bi-pencil"></i>
        </a>

        {{-- Delete --}}
        <form method="POST"
              action="{{ route('admin.workshops.destroy', $workshop) }}"
              onsubmit="return confirm('Are you sure you want to delete this workshop?');">
            @csrf
            @method('DELETE')

            <button type="submit"
                    class="btn btn-outline-danger"
                    title="Delete Workshop"
                    aria-label="Delete Workshop">
                <i class="bi bi-trash"></i>
            </button>
        </form>

    </div>
</td>

                        </tr>
                    @empty
                        <tr><td colspan="9" class="p-0">
                            @include('partials.empty-state', ['icon' => 'bi-mortarboard', 'message' => 'No workshops match your filters.'])
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white">{{ $workshops->links() }}</div>
    </div>
</div>
@endsection
