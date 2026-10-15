@php
    $t = $template ?? null;
    $cfg = old('config', $t?->configuration ?? []);
    $fields = [
        'student_name' => 'Student Name',
        'workshop_name' => 'Workshop Name',
        'college_name' => 'College Name',
        'course' => 'Course',
        'branch' => 'Branch',
        'year' => 'Year',
        'trainer_name' => 'Trainer Name',
        'date' => 'Date (Workshop Date)',
        'issue_date' => 'Issue Date (Registration Date)',
        'duration' => 'Duration',
        'certificate_id' => 'Certificate ID',
        'enrollment_id' => 'Enrollment ID',
    ];
@endphp

<div class="row g-3 mb-4">
    <div class="col-md-8">
        <label class="form-label">Template Name</label>
        <input type="text" name="name" value="{{ old('name', $t?->name) }}"
               class="form-control @error('name') is-invalid @enderror" required>
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4 d-flex align-items-end">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="status" value="1" id="status"
                   @checked(old('status', $t?->status ?? true))>
            <label class="form-check-label" for="status">Active</label>
        </div>
    </div>

    <div class="col-12">
        <label class="form-label">Certificate PDF {{ $t ? '(leave blank to keep current file)' : '' }}</label>
        <input type="file" name="file" accept="application/pdf" class="form-control @error('file') is-invalid @enderror" {{ $t ? '' : 'required' }}>
        @error('file') <div class="invalid-feedback">{{ $message }}</div> @enderror
        <div class="form-text">PDF only, max 10 MB. This is the fixed-design background every certificate is generated on top of.</div>
    </div>
</div>

<h2 class="h6 mb-3">Field Positions</h2>
<p class="text-muted small">
    Coordinates are in points, measured from the <strong>top-left</strong> corner of the page.
    Leave a field's X/Y blank to skip printing it on this template. Use <strong>Preview</strong>
    after saving to check alignment with sample data before generating real certificates.
</p>

@php
    $fontOptions = [
        'dejavusans' => 'Sans-serif (default)',
        'times' => 'Times New Roman (serif)',
        'helvetica' => 'Helvetica / Arial',
        'courier' => 'Courier (monospace)',
        'freeserif' => 'Free Serif',
          'nunito' => 'Nunito',
          'nunitob' => 'Nunito Bold',
          'nunitoi' => 'Nunito Italic',
          'nunitobi' => 'Nunito Bold+Italic',
    ];
@endphp

<div class="table-responsive mb-4">
    <table class="table align-middle">
        <thead class="text-muted small text-uppercase">
            <tr>
                <th>Field</th><th style="width:100px;">X</th><th style="width:100px;">Y</th>
                <th style="width:100px;">Size</th><th style="width:80px;">Color</th>
                <th style="width:170px;">Font</th><th style="width:90px;">Style</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($fields as $key => $label)
                <tr>
                    <td>{{ $label }}</td>
                    <td><input type="number" step="0.1" name="config[{{ $key }}][x]" value="{{ $cfg[$key]['x'] ?? '' }}" class="form-control form-control-sm"></td>
                    <td><input type="number" step="0.1" name="config[{{ $key }}][y]" value="{{ $cfg[$key]['y'] ?? '' }}" class="form-control form-control-sm"></td>
                    <td><input type="number" step="0.1" name="config[{{ $key }}][font_size]" value="{{ $cfg[$key]['font_size'] ?? 16 }}" class="form-control form-control-sm"></td>
                    <td><input type="color" name="config[{{ $key }}][color]" value="{{ $cfg[$key]['color'] ?? '#000000' }}" class="form-control form-control-sm form-control-color" title="Text color"></td>
                    <td>
                        <select name="config[{{ $key }}][font_family]" class="form-select form-select-sm">
                            @foreach ($fontOptions as $value => $fontLabel)
                                <option value="{{ $value }}" @selected(($cfg[$key]['font_family'] ?? 'dejavusans') === $value)>{{ $fontLabel }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="config[{{ $key }}][bold]" value="1" id="{{ $key }}_bold" @checked($cfg[$key]['bold'] ?? false)>
                            <label class="form-check-label small" for="{{ $key }}_bold">B</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="config[{{ $key }}][italic]" value="1" id="{{ $key }}_italic" @checked($cfg[$key]['italic'] ?? false)>
                            <label class="form-check-label small" for="{{ $key }}_italic">I</label>
                        </div>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td>Verification QR</td>
                <td><input type="number" step="0.1" name="config[verification_qr][x]" value="{{ $cfg['verification_qr']['x'] ?? '' }}" class="form-control form-control-sm"></td>
                <td><input type="number" step="0.1" name="config[verification_qr][y]" value="{{ $cfg['verification_qr']['y'] ?? '' }}" class="form-control form-control-sm"></td>
                <td>
                    <input type="number" step="1" name="config[verification_qr][size]" value="{{ $cfg['verification_qr']['size'] ?? 70 }}"
                           class="form-control form-control-sm" placeholder="Size (pt)">
                </td>
                <td colspan="2" class="text-muted small">N/A</td>
            </tr>
        </tbody>
    </table>
</div>
