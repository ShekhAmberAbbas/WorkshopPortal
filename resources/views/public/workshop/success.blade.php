<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Successful — {{ $workshop->title }}</title>
    <link rel="icon" href="{{ asset('images/qwaretech-hub-favicon.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Karla:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --brand:#005495; --dark:#003b68; }
        body { background:#f6f9fc; font-family:'Karla',system-ui,sans-serif; }
        h1 { font-family:'Fraunces',serif; }
        .brand-logo { max-width:220px; max-height:55px; object-fit:contain; }
        .success-card { border:0; border-radius:1.25rem; box-shadow:0 12px 40px rgba(16,42,67,.08); overflow:hidden; }
        .success-head { background:linear-gradient(135deg,#005495,#003b68); color:#fff; }
        .check-circle { width:72px; height:72px; border-radius:50%; background:#ecfdf5; color:#059669; display:flex; align-items:center; justify-content:center; font-size:36px; margin:0 auto 1rem; }
        .detail { border:1px solid #e5edf4; border-radius:.8rem; padding:.8rem .9rem; height:100%; }
        .detail-label { font-size:.72rem; color:#718096; text-transform:uppercase; font-weight:700; letter-spacing:.04em; }
        .detail-value { font-weight:700; color:#243447; margin-top:.2rem; }
        .btn-brand { background:var(--brand); border-color:var(--brand); }
        .btn-brand:hover { background:var(--dark); border-color:var(--dark); }
    </style>
</head>
<body>
<div class="container py-4 py-md-5" style="max-width:760px;">
    <div class="text-center mb-4"><img src="{{ asset('images/qwaretech-hub-logo.png') }}" alt="Quaere Tech Hub" class="brand-logo"></div>

    <div class="card success-card">
        <div class="success-head text-center p-4 p-md-5">
            <div class="check-circle"><i class="bi bi-check-lg"></i></div>
            <h1 class="h4 mb-2">Form Submission Successful</h1>
            <p class="mb-0 text-white-50">Thank you for your registration, {{ $participant->name }}.</p>
        </div>

        <div class="card-body p-4 p-md-5">
            <div class="row g-3 mb-4">
                <div class="col-md-6"><div class="detail"><div class="detail-label">Workshop Topic</div><div class="detail-value">{{ $workshop->title }}</div></div></div>
                <div class="col-md-6"><div class="detail"><div class="detail-label">College Name</div><div class="detail-value">{{ $workshop->college_name }}</div></div></div>
                <div class="col-md-4"><div class="detail"><div class="detail-label">Start Date</div><div class="detail-value">{{ $workshop->start_date->format('d M Y') }}</div></div></div>
                <div class="col-md-4"><div class="detail"><div class="detail-label">End Date</div><div class="detail-value">{{ $workshop->end_date->format('d M Y') }}</div></div></div>
                <div class="col-md-4"><div class="detail"><div class="detail-label">Duration</div><div class="detail-value">{{ $workshop->duration_label }}</div></div></div>
                @if ($participant->enrollment_id)
                    <div class="col-12"><div class="detail"><div class="detail-label">Enrollment ID</div><div class="detail-value"><code>{{ $participant->enrollment_id }}</code></div></div></div>
                @endif
            </div>

            @if ($workshop->certificate_enabled)
                @if (! $workshop->certificatesAvailableForDownload())
                    <div class="alert alert-info small mb-3">
                        <i class="bi bi-clock me-1"></i>
                        Your certificate will be available to download after the workshop ends on
                        {{ $workshop->computed_end_date?->format('d M Y') ?? 'the workshop end date' }}.
                    </div>
                @elseif ($participant->certificate?->status === 'generated')
                    <div class="alert alert-success small mb-3"><i class="bi bi-patch-check-fill me-1"></i>Your certificate is ready to download.</div>
                    <a href="{{ route('certificate.download', $participant->certificate) }}" class="btn btn-brand text-white w-100 py-2 mb-3"><i class="bi bi-download me-1"></i> Download Certificate</a>
                @else
                    <div class="alert alert-light border small mb-3"><i class="bi bi-hourglass-split me-1"></i>Your workshop has ended. Your certificate is still being processed.</div>
                @endif
            @endif


        </div>
    </div>
</div>
</body>
</html>
