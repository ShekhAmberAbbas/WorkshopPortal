@extends('layouts.admin')

@section('title', 'Academic Options')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <div class="small text-uppercase fw-semibold text-primary">Configuration</div>
        <h1 class="h3 mb-1">Academic Options</h1>
        <p class="text-muted mb-0">Manage the course/program list and the branches available under each course.</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-5">
        <div class="card stat-card h-100">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="h5 mb-1">Courses / Programs</h2>
                        <div class="small text-muted">Students see only active options.</div>
                    </div>
                    <span class="badge text-bg-light border">{{ $courses->count() }}</span>
                </div>
            </div>
            <div class="card-body px-4">
                <form method="POST" action="{{ route('admin.academic.courses.store') }}" class="border rounded-4 p-3 mb-4 bg-light-subtle">
                    @csrf
                    <div class="row g-2 align-items-end">
                        <div class="col-md">
                            <label class="form-label small fw-semibold">Course / Program</label>
                            <input name="name" class="form-control" maxlength="100" placeholder="e.g. B.Tech" required>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add</button>
                        </div>
                    </div>
                </form>

                <div class="vstack gap-2">
                    @forelse($courses as $course)
                        <div class="border rounded-4 p-3">
                            <div class="d-flex justify-content-between gap-3 align-items-start">
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">{{ $course->name }}</div>
                                    <div class="small text-muted mt-1">{{ $course->branches_count }} active {{ Str::plural('branch', $course->branches_count) }}</div>
                                </div>
                                <span class="badge rounded-pill {{ $course->status ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $course->status ? 'Active' : 'Inactive' }}</span>
                            </div>
                            <form method="POST" action="{{ route('admin.academic.courses.update', $course) }}" class="row g-2 mt-2">
                                @csrf @method('PATCH')
                                <div class="col">
                                    <input name="name" value="{{ $course->name }}" class="form-control form-control-sm" maxlength="100" required>
                                </div>
                                <input type="hidden" name="status" value="{{ $course->status ? 1 : 0 }}">
                                <div class="col-auto"><button class="btn btn-outline-primary btn-sm" title="Save changes"><i class="bi bi-check2"></i></button></div>
                            </form>
                            <form method="POST" action="{{ route('admin.academic.courses.toggle', $course) }}" class="mt-2">
                                @csrf
                                <button class="btn btn-sm {{ $course->status ? 'btn-outline-danger' : 'btn-outline-success' }}">
                                    <i class="bi {{ $course->status ? 'bi-pause-circle' : 'bi-play-circle' }} me-1"></i>
                                    {{ $course->status ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="text-center text-muted py-5">No courses configured yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-7">
        <div class="card stat-card h-100">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="h5 mb-1">Branches / Specializations</h2>
                        <div class="small text-muted">A branch belongs to exactly one course.</div>
                    </div>
                    <span class="badge text-bg-light border">{{ $branches->count() }}</span>
                </div>
            </div>
            <div class="card-body px-4">
                <form method="POST" action="{{ route('admin.academic.branches.store') }}" class="border rounded-4 p-3 mb-4 bg-light-subtle">
                    @csrf
                    <div class="row g-2 align-items-end">
                        <div class="col-md-5">
                            <label class="form-label small fw-semibold">Course / Program</label>
                            <select name="course_id" class="form-select" required>
                                <option value="">Select Course</option>
                                @foreach($courses as $course)
                                    @if($course->status)
                                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md">
                            <label class="form-label small fw-semibold">Branch</label>
                            <input name="name" class="form-control" maxlength="150" placeholder="e.g. Computer Science & Engineering" required>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add</button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="text-muted small text-uppercase">
                        <tr><th>Course</th><th>Branch</th><th>Status</th><th class="text-end">Actions</th></tr>
                        </thead>
                        <tbody>
                        @forelse($branches as $branch)
                            <tr>
                                <td class="fw-semibold">{{ $branch->course?->name ?? '—' }}</td>
                                <td>
                                    <form method="POST" action="{{ route('admin.academic.branches.update', $branch) }}" class="d-flex gap-2 align-items-center">
                                        @csrf @method('PATCH')
                                        <select name="course_id" class="form-select form-select-sm" style="max-width:220px;" required>
                                            @foreach($courses as $course)
                                                <option value="{{ $course->id }}" @selected($branch->course_id === $course->id)>{{ $course->name }}</option>
                                            @endforeach
                                        </select>
                                        <input name="name" value="{{ $branch->name }}" class="form-control form-control-sm" maxlength="150" required>
                                        <input type="hidden" name="status" value="{{ $branch->status ? 1 : 0 }}">
                                        <button class="btn btn-outline-primary btn-sm" title="Save changes"><i class="bi bi-check2"></i></button>
                                    </form>
                                </td>
                                <td><span class="badge rounded-pill {{ $branch->status ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $branch->status ? 'Active' : 'Inactive' }}</span></td>
                                <td class="text-end">
                                    <form method="POST" action="{{ route('admin.academic.branches.toggle', $branch) }}">
                                        @csrf
                                        <button class="btn btn-sm {{ $branch->status ? 'btn-outline-danger' : 'btn-outline-success' }}">
                                            {{ $branch->status ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-5">No branches configured yet.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
