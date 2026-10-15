@extends('layouts.admin')

@section('title', 'Certificates — ' . $workshop->title)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.workshops.show', $workshop) }}" class="text-decoration-none text-muted small">
                <i class="bi bi-arrow-left me-1"></i> {{ $workshop->title }}
            </a>
            <h1 class="h3 mb-0 mt-1">Certificates</h1>
        </div>
        <a href="{{ route('admin.workshops.certificates.export', $workshop) }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-download me-1"></i> Export Excel
        </a>
    </div>

    @if ($ungeneratedCount > 0 && $workshop->certificateTemplate && $workshop->certificateTemplate->status)
        <div class="card stat-card mb-4">
            <div class="card-body">
                <h2 class="h6 mb-2">Bulk Generate</h2>
                <p class="text-muted small mb-3">
                    {{ $ungeneratedCount }} {{ Str::plural('participant', $ungeneratedCount) }} without a certificate yet —
                    will use this workshop's assigned template, "<strong>{{ $workshop->certificateTemplate->name }}</strong>".
                </p>
                <form method="POST" action="{{ route('admin.workshops.certificates.bulk-generate', $workshop) }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        Generate All ({{ $ungeneratedCount }})
                    </button>
                </form>
            </div>
        </div>
    @elseif ($ungeneratedCount > 0)
        <div class="alert alert-warning">
            {{ $ungeneratedCount }} {{ Str::plural('participant', $ungeneratedCount) }} without a certificate —
            assign an active certificate template on this workshop's
            <a href="{{ route('admin.workshops.edit', $workshop) }}">Edit page</a> first.
        </div>
    @endif

    <div class="card stat-card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="text-muted small text-uppercase">
                    <tr><th>Certificate ID</th><th>Participant</th><th>Issue Date</th><th>Status</th><th class="text-end">Actions</th></tr>
                </thead>
                <tbody>
                    @forelse ($certificates as $certificate)
                        <tr>
                            <td><code>{{ $certificate->certificate_id }}</code></td>
                            <td>{{ $certificate->participant->name }}</td>
                            <td>{{ $certificate->issue_date->format('d M Y') }}</td>
                            <td>
                                <span class="badge text-bg-{{ $certificate->status === 'generated' ? 'success' : ($certificate->status === 'revoked' ? 'danger' : 'secondary') }}">
                                    {{ ucfirst($certificate->status) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('certificate.verify', $certificate) }}" target="_blank" class="btn btn-outline-secondary" title="View verification page">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if ($certificate->status === 'generated')
                                        @if ($workshop->certificatesAvailableForDownload())
                                            <a href="{{ route('admin.certificates.download', $certificate) }}" class="btn btn-outline-primary" title="Download">
                                                <i class="bi bi-download"></i>
                                            </a>
                                        @else
                                            <button type="button" class="btn btn-outline-secondary" disabled title="Available from {{ $workshop->start_date?->format('d M Y') }}">
                                                <i class="bi bi-lock"></i>
                                            </button>
                                        @endif
                                        <form method="POST" action="{{ route('admin.certificates.revoke', $certificate) }}" onsubmit="return confirm('Revoke this certificate? The verification page will show it as revoked.');">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger" title="Revoke"><i class="bi bi-x-circle"></i></button>
                                        </form>
                                    @elseif ($certificate->status === 'revoked')
                                        <form method="POST" action="{{ route('admin.certificates.reactivate', $certificate) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success" title="Reactivate"><i class="bi bi-arrow-counterclockwise"></i></button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="p-0">
                            @include('partials.empty-state', ['icon' => 'bi-patch-check', 'message' => 'No certificate records are available for this workshop yet.'])
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white">{{ $certificates->links() }}</div>
    </div>
@endsection
