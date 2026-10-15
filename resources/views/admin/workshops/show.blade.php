@extends('layouts.admin')

@section('title', $workshop->title)

@section('content')
@push('styles')
<style>
    .workshop-show-hero { background:linear-gradient(135deg,#005495,#003b68); color:#fff; border-radius:1rem; padding:1.25rem 1.35rem; position:relative; overflow:hidden; }
    .workshop-show-hero::after { content:""; position:absolute; width:260px; height:260px; right:-100px; top:-160px; border-radius:50%; background:rgba(255,255,255,.08); }
    .workshop-show-hero .logo { max-height:48px; max-width:210px; background:#fff; border-radius:.5rem; padding:.3rem .5rem; object-fit:contain; }
    .meta-card { border:1px solid #e8eef4; border-radius:1rem; background:#fff; }
    .meta-label { font-size:.74rem; color:#718096; text-transform:uppercase; letter-spacing:.04em; font-weight:700; }
    .meta-value { font-weight:700; color:#243447; }
</style>
@endpush

<div class="workshop-show-hero mb-4 d-flex justify-content-between align-items-start gap-3">
    <div class="position-relative z-1">
        <div class="small text-white-50 text-uppercase fw-semibold mb-1">Workshop Topic</div>
        <h1 class="h3 mb-1">{{ $workshop->title }}</h1>
        <div class="text-white-50">{{ $workshop->college_name }}</div>
        <div class="small text-white-50 mt-2">
            {{ $workshop->start_date->format('d M Y') }} – {{ $workshop->end_date->format('d M Y') }}
            &middot; {{ $workshop->duration_label }}
        </div>
    </div>
    <img class="logo position-relative z-1" src="{{ asset('images/qwaretech-hub-logo.png') }}" alt="Quaere Tech Hub" onerror="this.style.display='none'">
</div>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <span class="badge rounded-pill text-bg-light border"><i class="bi bi-upc-scan me-1"></i>{{ $workshop->workshop_code }}</span>
    </div>
    <div class="d-flex gap-2">
        <form method="POST" action="{{ route('admin.workshops.toggle-status', $workshop) }}" class="d-flex gap-1">
            @csrf
            <select name="status" class="form-select form-select-sm" style="width:140px;" onchange="this.form.submit()" aria-label="Workshop status">
                @foreach (['draft', 'active', 'inactive', 'completed'] as $status)
                    <option value="{{ $status }}" @selected($workshop->status === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </form>
        <a href="{{ route('admin.workshops.edit', $workshop) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil me-1"></i> Edit</a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6"><div class="card stat-card h-100"><div class="card-body"><div class="value">{{ $workshop->participants_count }}</div><div class="text-muted small">Participants</div></div></div></div>
    <div class="col-md-6"><div class="card stat-card h-100"><div class="card-body"><div class="value">{{ $workshop->certificates_count }}</div><div class="text-muted small">Certificates Generated</div></div></div></div>
</div>

<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#overview">Overview</button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#qr">QR / Registration</button></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('admin.workshops.participants.index', $workshop) }}">Participants</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('admin.workshops.certificates.index', $workshop) }}">Certificates</a></li>
</ul>

<div class="tab-content bg-white border border-top-0 rounded-bottom p-4">
    <div class="tab-pane fade show active" id="overview">
        <div class="row g-3">
            <div class="col-md-6"><div class="meta-card p-3"><div class="meta-label">College Name</div><div class="meta-value mt-1">{{ $workshop->college_name }}</div></div></div>
            <div class="col-md-6"><div class="meta-card p-3"><div class="meta-label">Workshop Code</div><div class="meta-value mt-1"><code>{{ $workshop->workshop_code }}</code></div></div></div>
            <div class="col-md-4"><div class="meta-card p-3"><div class="meta-label">Start Date</div><div class="meta-value mt-1">{{ $workshop->start_date->format('d M Y') }}</div></div></div>
            <div class="col-md-4"><div class="meta-card p-3"><div class="meta-label">End Date</div><div class="meta-value mt-1">{{ $workshop->end_date->format('d M Y') }}</div></div></div>
            <div class="col-md-4"><div class="meta-card p-3"><div class="meta-label">Duration</div><div class="meta-value mt-1">{{ $workshop->duration_label }}</div></div></div>
            <div class="col-md-6"><div class="meta-card p-3"><div class="meta-label">Trainer</div><div class="meta-value mt-1">{{ $workshop->trainer_name }}{{ $workshop->trainer_designation ? ', ' . $workshop->trainer_designation : '' }}</div></div></div>
            <div class="col-md-6"><div class="meta-card p-3"><div class="meta-label">Maximum Participants</div><div class="meta-value mt-1">{{ $workshop->max_participants ?? 'No limit' }}</div></div></div>
            <div class="col-12"><div class="meta-card p-3"><div class="meta-label">Description</div><div class="mt-1">{{ $workshop->description ?: 'No description added.' }}</div></div></div>
        </div>
    </div>

    <div class="tab-pane fade" id="qr">
        <div class="row g-4 align-items-center">
            <div class="col-md-7">
                <label class="form-label small text-muted">Registration URL</label>
                <div class="input-group mb-3">
                    <input type="text" id="registration-url" class="form-control" readonly value="{{ $workshop->registrationUrl() }}">
                    <button class="btn btn-outline-secondary" type="button" onclick="copyRegistrationUrl(this)"><i class="bi bi-clipboard"></i> Copy</button>
                </div>
                <p class="text-muted small">Students can scan the QR code or open this link to register directly for this workshop.</p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.workshops.qr.download', ['workshop' => $workshop, 'format' => 'svg']) }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-download me-1"></i> Download SVG</a>
                    <a href="{{ route('admin.workshops.qr.print', $workshop) }}" target="_blank" class="btn btn-outline-secondary btn-sm"><i class="bi bi-printer me-1"></i> Print</a>
                </div>
            </div>
            <div class="col-md-5 text-center">
                <img
                    src="{{ route('admin.workshops.qr.image', $workshop) }}"
                    alt="Workshop registration QR code"
                    class="img-fluid border rounded p-2"
                    style="max-width:260px; width:100%; height:auto;"
                    loading="eager"
                >
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function copyRegistrationUrl(button) {
    const input = document.getElementById('registration-url');
    if (!input) return;
    navigator.clipboard.writeText(input.value).then(() => {
        const original = button.innerHTML;
        button.innerHTML = '<i class="bi bi-check2"></i> Copied';
        setTimeout(() => button.innerHTML = original, 1500);
    });
}
</script>
@endpush
