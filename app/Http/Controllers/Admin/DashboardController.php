<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Participant;
use App\Models\Workshop;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $stats = [
            'total_workshops' => Workshop::count(),
            'total_participants' => Participant::count(),
            'certificates_generated' => Certificate::where('status', 'generated')->count(),
            'total_college' => Workshop::whereNotNull('college_name')->where('college_name', '!=', '')->distinct('college_name')->count('college_name'),
            'certificates_pending' => Certificate::where('status', 'pending')->count(),
        ];

        $recentWorkshops = Workshop::withCount(['participants', 'certificates'])
            ->latest()
            ->take(10)
            ->get();

        $months = collect(range(5, 0))->map(fn ($i) => Carbon::now()->subMonths($i)->startOfMonth());

        // Computed in PHP rather than a raw GROUP BY DATE_FORMAT — keeps this
        // portable across MySQL/SQLite/Postgres without dialect-specific SQL.
        $monthlyTrend = $months->map(function (Carbon $month) {
            $start = $month->copy()->startOfMonth();
            $end = $month->copy()->endOfMonth();

            return [
                'label' => $month->format('M Y'),
                'workshops' => Workshop::whereBetween('created_at', [$start, $end])->count(),
                'participants' => Participant::whereBetween('registered_at', [$start, $end])->count(),
                'certificates' => Certificate::where('status', 'generated')->whereBetween('created_at', [$start, $end])->count(),
            ];
        });

        $collegeWise = Participant::selectRaw('college_name, count(*) as total')
            ->groupBy('college_name')
            ->orderByDesc('total')
            ->take(6)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentWorkshops', 'monthlyTrend', 'collegeWise'));
    }
}
