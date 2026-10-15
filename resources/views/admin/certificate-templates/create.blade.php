@extends('layouts.admin')

@section('title', 'Upload Certificate Template')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Upload Certificate Template</h1>
        <a href="{{ route('admin.certificate-templates.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="card stat-card">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.certificate-templates.store') }}" enctype="multipart/form-data">
                @csrf
                @include('admin.certificate-templates._form')
                <button type="submit" class="btn btn-primary">Save Template</button>
            </form>
        </div>
    </div>
@endsection
