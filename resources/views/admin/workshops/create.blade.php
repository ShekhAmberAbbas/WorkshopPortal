@extends('layouts.admin')

@section('title', 'Create Workshop')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Create Workshop</h1>
        <a href="{{ route('admin.workshops.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to list
        </a>
    </div>

    <div class="card stat-card">
        <div class="card-body p-4">
            <form data-client-validation="true" method="POST" action="{{ route('admin.workshops.store') }}">
                @csrf
                @include('admin.workshops._form')

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check2-circle me-1"></i> Create Workshop
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
