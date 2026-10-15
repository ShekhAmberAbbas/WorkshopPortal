<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate Verification — {{ $certificate->certificate_id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Karla:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body { background: #f7f8fb; font-family: 'Karla', system-ui, sans-serif; }
        h1 { font-family: 'Fraunces', serif; }
        code { font-family: 'JetBrains Mono', monospace; }
        .verified-badge {
            width: 64px; height: 64px; border-radius: 50%;
            background: #ecfdf5; color: #059669;
            display: flex; align-items: center; justify-content: center;
            font-size: 32px; margin: 0 auto 1rem;
        }
    </style>
</head>
<body>
    <div class="container py-5" style="max-width: 480px;">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 text-center">
                @if ($certificate->status === 'revoked')
                    <div class="verified-badge" style="background:#fef2f2;color:#dc2626;"><i class="bi bi-x-circle-fill"></i></div>
                    <h1 class="h5 mb-1">Certificate Revoked</h1>
                    <p class="text-muted small mb-4">This certificate is no longer valid.</p>
                @else
                    <div class="verified-badge"><i class="bi bi-patch-check-fill"></i></div>
                    <h1 class="h5 mb-1">Certificate Verified</h1>
                    <p class="text-muted small mb-4">This information is sourced directly from our records.</p>
                @endif

                <dl class="row text-start small mb-0">
                    <dt class="col-5 text-muted">Certificate ID</dt>
                    <dd class="col-7"><code>{{ $certificate->certificate_id }}</code></dd>

                    <dt class="col-5 text-muted">Participant</dt>
                    <dd class="col-7">{{ $certificate->participant->name }}</dd>

                    <dt class="col-5 text-muted">Workshop</dt>
                    <dd class="col-7">{{ $certificate->workshop->title }}</dd>

                    <dt class="col-5 text-muted">College</dt>
                    <dd class="col-7">{{ $certificate->participant->college_name }}</dd>

                    <dt class="col-5 text-muted">Workshop Date</dt>
                    <dd class="col-7">{{ $certificate->workshop->start_date->format('d M Y') }}</dd>

                    <dt class="col-5 text-muted">Duration</dt>
                    <dd class="col-7">{{ $certificate->workshop->duration_label }} {{ Str::plural('Day', $certificate->workshop->duration_label) }}</dd>

                    <dt class="col-5 text-muted">Issued On</dt>
                    <dd class="col-7">{{ $certificate->issue_date->format('d M Y') }}</dd>
                </dl>
            </div>
        </div>
        <p class="text-center text-muted small mt-3">
            If details on a downloaded PDF don't match this page, this page is the authoritative record.
        </p>
    </div>
</body>
</html>
