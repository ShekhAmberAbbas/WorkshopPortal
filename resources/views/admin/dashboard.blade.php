@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <h1 class="h3 mb-4">Dashboard</h1>

    <div class="row g-3 mb-4">
        @php
            $cards = [
                ['label' => 'Total Workshops', 'value' => $stats['total_workshops'], 'icon' => 'bi-mortarboard'],
                ['label' => 'Total Participants', 'value' => $stats['total_participants'], 'icon' => 'bi-people'],
                ['label' => 'Total College', 'value' => $stats['total_college'], 'icon' => 'bi-building'],
                
            ];
        @endphp

        @foreach ($cards as $card)
            <div class="col-4 col-lg-4">
                <div class="card stat-card h-100">
                    <div class="card-body">
                        <i class="bi {{ $card['icon'] }} text-primary mb-2"></i>
                        <div class="value">{{ $card['value'] }}</div>
                        <div class="text-muted small">{{ $card['label'] }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card stat-card">
        <div class="card-header bg-white border-0 pt-3">
            <h2 class="h5 mb-0">Recent Workshops</h2>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
               <thead class="text-muted small text-uppercase">
    <tr>
        <th>Workshop Topic</th>
        <th>College</th>
        <th>Dates</th>
        <th>Duration</th>
        <th>Participants</th>
        <th>Certificates</th>
        <th>Status</th>
    </tr>
</thead>
                <tbody>
                   @forelse ($recentWorkshops as $workshop)
    <tr>
        <td>
    <a href="{{ route('admin.workshops.show', $workshop) }}"
       class="fw-semibold text-decoration-none dashboard-topic-link"
       title="View Workshop">
        {{ $workshop->title }}
    </a>

    <div class="small text-muted">
        <code>{{ $workshop->workshop_code }}</code>
    </div>
</td>

        <td>{{ $workshop->college_name }}</td>

        <td>
            <div class="fw-semibold">
                {{ $workshop->start_date?->format('d M Y') ?? '—' }}
            </div>

            @if ($workshop->end_date)
                <div class="small text-muted">
                    to {{ $workshop->end_date->format('d M Y') }}
                </div>
            @endif
        </td>

        <td>
            <span class="badge bg-light text-dark">
                {{ $workshop->duration_label }}
            </span>
        </td>

        <td>{{ $workshop->participants_count }}</td>

        <td>{{ $workshop->certificates_count }}</td>

        <td>
            <span class="badge text-bg-{{ $workshop->status === 'active' ? 'success' : 'secondary' }}">
                {{ ucfirst($workshop->status) }}
            </span>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="p-0">
            @include('partials.empty-state', [
                'icon' => 'bi-mortarboard',
                'message' => 'No recent workshops found.'
            ])
        </td>
    </tr>
@endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-lg-8">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <h2 class="h6 mb-3">Workshops, Participants &amp; Certificates (Last 6 Months)</h2>
                    <canvas id="trendChart" height="90"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <h2 class="h6 mb-3">College-wise Participation</h2>
                    @if ($collegeWise->isEmpty())
                        <p class="text-muted small mb-0">No participants yet.</p>
                    @else
                        <canvas id="collegeChart" height="140"></canvas>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    const trendLabels = @json($monthlyTrend->pluck('label'));

    new Chart(document.getElementById('trendChart'), {
        type: 'line',
        data: {
            labels: trendLabels,
            datasets: [
                { label: 'Workshops', data: @json($monthlyTrend->pluck('workshops')), borderColor: '#4f46e5', tension: 0.3 },
                { label: 'Participants', data: @json($monthlyTrend->pluck('participants')), borderColor: '#059669', tension: 0.3 },
                { label: 'Certificates', data: @json($monthlyTrend->pluck('certificates')), borderColor: '#d97706', tension: 0.3 },
            ],
        },
        options: { scales: { y: { beginAtZero: true } } },
    });

    @if ($collegeWise->isNotEmpty())
    new Chart(document.getElementById('collegeChart'), {
        type: 'bar',
        data: {
            labels: @json($collegeWise->pluck('college_name')),
            datasets: [{ label: 'Participants', data: @json($collegeWise->pluck('total')), backgroundColor: '#4f46e5' }],
        },
        options: {
            indexAxis: 'y',
            scales: { x: { beginAtZero: true } },
            plugins: { legend: { display: false } },
        },
    });
    @endif
</script>
@endpush
