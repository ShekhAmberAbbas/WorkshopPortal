@extends('layouts.admin')

@section('title', 'Edit Certificate Template')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Edit Certificate Template</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.certificate-templates.preview', $template) }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-eye me-1"></i> Preview with Sample Data
            </a>
            <a href="{{ route('admin.certificate-templates.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    <div class="card stat-card">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.certificate-templates.update', $template) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('admin.certificate-templates._form')
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>
        </div>
    </div>
@endsection
