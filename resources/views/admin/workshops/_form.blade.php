@php
    $w = $workshop ?? $w ?? null;
    $startDate = old('start_date', $w?->start_date?->format('Y-m-d'));
    $endDate = old('end_date', $w?->end_date?->format('Y-m-d'));
@endphp

@push('styles')
<style>
    .workshop-form-shell { --wf-brand:#005495; --wf-dark:#003b68; }
    .workshop-brand {
        background: linear-gradient(135deg, #005495 0%, #003b68 100%);
        border-radius: 1rem;
        padding: 1rem 1.25rem;
        color: #fff;
        position: relative;
        overflow: hidden;
    }
    .workshop-brand::after {
        content:""; position:absolute; width:220px; height:220px; right:-80px; top:-130px;
        border-radius:50%; background:rgba(255,255,255,.08);
    }
    .workshop-brand img { max-height:54px; max-width:250px; background:#fff; border-radius:.55rem; padding:.35rem .55rem; object-fit:contain; }
    .form-section { border:1px solid #e8eef4; border-radius:1rem; background:#fff; overflow:hidden; }
    .form-section-head { padding:.9rem 1.1rem; background:#f7fafc; border-bottom:1px solid #e8eef4; }
    .form-section-head .icon {
        width:34px; height:34px; display:inline-flex; align-items:center; justify-content:center;
        border-radius:.65rem; background:#eaf4fb; color:#005495;
    }
    .form-section-body { padding:1.1rem; }
    .form-label { font-weight:600; color:#243447; }
    .form-control, .form-select { min-height:44px; border-color:#d8e3ec; border-radius:.65rem; }
    textarea.form-control { min-height:auto; }
    .form-control:focus, .form-select:focus { border-color:#005495; box-shadow:0 0 0 .2rem rgba(0,84,149,.12); }
    .duration-box { background:#f0f8ff; border:1px dashed #8fc8ea; color:#005495; font-weight:700; }
    .required-mark { color:#dc3545; }
    .form-hint { font-size:.78rem; color:#718096; margin-top:.35rem; }
    .switch-card { border:1px solid #e8eef4; border-radius:.8rem; padding:.9rem; height:100%; }
</style>
@endpush

<div class="workshop-form-shell">
    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm mb-4" role="alert">
            <div class="d-flex gap-2">
                <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                <div>
                    <strong>Please review the highlighted fields.</strong>
                    <ul class="mb-0 mt-1 ps-3 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="workshop-brand mb-4 d-flex align-items-center justify-content-between gap-3">
        <div class="position-relative z-1">
            <div class="small text-white-50 text-uppercase fw-semibold mb-1">Quaere Tech Hub</div>
            <div class="h5 mb-0">{{ $w ? 'Update Workshop' : 'Create Workshop' }}</div>
            <div class="small text-white-50 mt-1">Build a clean, registration-ready workshop profile.</div>
        </div>
        <img src="{{ asset('images/qwaretech-hub-logo.png') }}" alt="Quaere Tech Hub" onerror="this.style.display='none'">
    </div>

    <div class="form-section mb-3">
        <div class="form-section-head d-flex align-items-center gap-2">
            <span class="icon"><i class="bi bi-mortarboard-fill"></i></span>
            <div><div class="fw-bold">Workshop Information</div><div class="small text-muted">Basic details students will see during registration.</div></div>
        </div>
        <div class="form-section-body">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Workshop Topic <span class="required-mark">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $w?->title) }}"
                           class="form-control @error('title') is-invalid @enderror" maxlength="191"
                           placeholder="e.g. Introduction to Artificial Intelligence" required autofocus>
                    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Description <span class="text-muted fw-normal">(optional)</span></label>
                    <textarea name="description" rows="3" maxlength="5000" class="form-control @error('description') is-invalid @enderror"
                              placeholder="Briefly describe the workshop, learning outcomes or activities.">{{ old('description', $w?->description) }}</textarea>
                    <div class="form-hint">Keep it concise and student-friendly.</div>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="form-section mb-3">
        <div class="form-section-head d-flex align-items-center gap-2">
            <span class="icon"><i class="bi bi-calendar3"></i></span>
            <div><div class="fw-bold">Schedule</div><div class="small text-muted">Duration is calculated automatically from the dates.</div></div>
        </div>
        <div class="form-section-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Start Date <span class="required-mark">*</span></label>
                    <input type="date" name="start_date" id="start_date" value="{{ $startDate }}"
                           class="form-control @error('start_date') is-invalid @enderror" required>
                    @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">End Date <span class="required-mark">*</span></label>
                    <input type="date" name="end_date" id="end_date" value="{{ $endDate }}"
                           class="form-control @error('end_date') is-invalid @enderror" required>
                    @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Duration</label>
                    <input type="text" id="duration_display" class="form-control duration-box" value="—" readonly aria-readonly="true">
                    <div class="form-hint">Automatically counted, inclusive of both dates.</div>
                </div>
            </div>
            <div id="date-client-error" class="alert alert-danger d-none mt-3 mb-0 py-2 small"></div>
        </div>
    </div>

    <div class="form-section mb-3">
        <div class="form-section-head d-flex align-items-center gap-2">
            <span class="icon"><i class="bi bi-building"></i></span>
            <div><div class="fw-bold">Institution</div><div class="small text-muted">The college name is shown to participants and stored with registrations.</div></div>
        </div>
        <div class="form-section-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">College Name <span class="required-mark">*</span></label>
                    <input type="text" name="college_name" value="{{ old('college_name', $w?->college_name) }}"
                           class="form-control @error('college_name') is-invalid @enderror" maxlength="191" required>
                    @error('college_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">College Address <span class="text-muted fw-normal">(optional)</span></label>
                    <input type="text" name="college_address" value="{{ old('college_address', $w?->college_address) }}"
                           class="form-control @error('college_address') is-invalid @enderror" maxlength="1000" placeholder="Optional internal reference">
                    @error('college_address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="form-section mb-3">
        <div class="form-section-head d-flex align-items-center gap-2">
            <span class="icon"><i class="bi bi-person-badge"></i></span>
            <div><div class="fw-bold">Trainer & Capacity</div><div class="small text-muted">Set the facilitator and participant limit.</div></div>
        </div>
        <div class="form-section-body">
            <div class="row g-3">
                <div class="col-md-5">
                    <label class="form-label">Trainer Name <span class="required-mark">*</span></label>
                    <input type="text" name="trainer_name" value="{{ old('trainer_name', $w?->trainer_name) }}"
                           class="form-control @error('trainer_name') is-invalid @enderror" maxlength="150" required>
                    @error('trainer_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Trainer Designation <span class="text-muted fw-normal">(optional)</span></label>
                    <input type="text" name="trainer_designation" value="{{ old('trainer_designation', $w?->trainer_designation) }}"
                           class="form-control @error('trainer_designation') is-invalid @enderror" maxlength="150">
                    @error('trainer_designation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Maximum Participants</label>
                    <input type="number" min="1" max="100000" name="max_participants" value="{{ old('max_participants', $w?->max_participants) }}"
                           class="form-control @error('max_participants') is-invalid @enderror" placeholder="No limit">
                    @error('max_participants') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="form-section mb-3">
        <div class="form-section-head d-flex align-items-center gap-2">
            <span class="icon"><i class="bi bi-sliders"></i></span>
            <div><div class="fw-bold">Workshop Settings</div><div class="small text-muted">Set workshop status and the certificate template used for every participant.</div></div>
        </div>
        <div class="form-section-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="switch-card h-100">
                        <div class="fw-semibold"><i class="bi bi-patch-check me-1 text-primary"></i>Certificate for every participant</div>
                        <div class="small text-muted mt-2">Every successful registration automatically receives a certificate. Select an active template below; certificate generation cannot be disabled.</div>
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Status <span class="required-mark">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        @foreach (['active', 'inactive'] as $status)
                            <option value="{{ $status }}" @selected(old('status', ($w?->status === 'active' ? 'active' : 'inactive')) === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-8" id="certificate-template-field">
                    <label class="form-label">Certificate Template <span class="required-mark">*</span></label>
                    <select name="certificate_template_id" required class="form-select @error('certificate_template_id') is-invalid @enderror">
                        <option value="">— Select a template —</option>
                        @foreach ($templates as $template)
                            <option value="{{ $template->id }}" @selected((int) old('certificate_template_id', $w?->certificate_template_id) === $template->id)>
                                {{ $template->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('certificate_template_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <div class="form-hint">
                        All certificates for this workshop use this assigned template.
                        @if ($templates->isEmpty())
                            <span class="text-danger d-block mt-1">No active templates found. Upload one before activating registration.</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
(() => {
    const start = document.getElementById('start_date');
    const end = document.getElementById('end_date');
    const duration = document.getElementById('duration_display');
    const dateError = document.getElementById('date-client-error');
    const form = start?.closest('form');

    function updateDuration() {
        if (!start || !end || !duration) return true;
        dateError?.classList.add('d-none');
        dateError && (dateError.textContent = '');

        if (!start.value || !end.value) {
            duration.value = '—';
            return true;
        }

        const a = new Date(start.value + 'T00:00:00');
        const b = new Date(end.value + 'T00:00:00');
        const diff = Math.round((b - a) / 86400000);

        if (diff < 0) {
            duration.value = 'Invalid date range';
            dateError?.classList.remove('d-none');
            if (dateError) dateError.textContent = 'End date cannot be before the start date.';
            end.classList.add('is-invalid');
            return false;
        }

        end.classList.remove('is-invalid');
        const days = diff + 1;
        duration.value = `${days} ${days === 1 ? 'Day' : 'Days'}`;
        return true;
    }

    start?.addEventListener('change', () => {
        if (start.value) end.min = start.value;
        updateDuration();
    });
    end?.addEventListener('change', updateDuration);
    updateDuration();

    form?.addEventListener('submit', (event) => {
        if (!updateDuration()) {
            event.preventDefault();
            end?.focus();
            dateError?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });

})();
</script>
@endpush
