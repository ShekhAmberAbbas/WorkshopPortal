@extends('layouts.admin')

@section('title', 'Certificate Templates')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Certificate Templates</h1>
        <a href="{{ route('admin.certificate-templates.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Upload Template
        </a>
    </div>

    <div class="card stat-card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="text-muted small text-uppercase">
                    <tr><th>Name</th><th>Used By</th><th>Certificates Generated</th><th>Status</th><th class="text-end">Actions</th></tr>
                </thead>
                <tbody>
                    @forelse ($templates as $template)
                        <tr>
                            <td>{{ $template->name }}</td>
                            <td>
                                @if ($template->workshops_count)
                                    <span class="badge text-bg-info-subtle text-dark">{{ $template->workshops_count }} {{ Str::plural('workshop', $template->workshops_count) }}</span>
                                @else
                                    <span class="text-muted small">Not assigned yet</span>
                                @endif
                            </td>
                            <td>{{ $template->certificates_count }}</td>
                            <td>
                                <span class="badge text-bg-{{ $template->status ? 'success' : 'secondary' }}">
                                    {{ $template->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.certificate-templates.preview', $template) }}" target="_blank" class="btn btn-outline-secondary" title="Preview">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.certificate-templates.edit', $template) }}" class="btn btn-outline-secondary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.certificate-templates.destroy', $template) }}"
                                          onsubmit="return confirm('Delete this template?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">No templates uploaded yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
