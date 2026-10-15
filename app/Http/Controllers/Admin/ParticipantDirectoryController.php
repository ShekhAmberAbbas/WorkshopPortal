<?php

namespace App\Http\Controllers\Admin;

use App\Exports\AllRegistrationsExport;
use App\Exports\UniqueStudentsExport;
use App\Http\Controllers\Controller;
use App\Models\Participant;
use App\Models\Workshop;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ParticipantDirectoryController extends Controller
{
    private const FILTER_KEYS = [
        'search',
        'workshop_id',
        'workshop_status',
        'college_name',
        'home_state',
        'home_district',
        'course',
        'branch',
        'year',
        'enrollment_number',
        'registered_from',
        'registered_to',
    ];

    public function index(Request $request)
    {
        if ($request->boolean('reset')) {
            $request->session()->forget('participant_directory');
            return redirect()->route('admin.participants.index');
        }

        $stored = $request->session()->get('participant_directory', []);

        if ($request->isMethod('post')) {
            $filters = $this->cleanFilters($request->only(self::FILTER_KEYS));
            $mode = in_array($request->input('mode'), ['all', 'unique'], true)
                ? $request->input('mode')
                : 'all';

            $request->session()->put('participant_directory', [
                'filters' => $filters,
                'mode' => $mode,
            ]);

            return redirect()->route('admin.participants.index');
        }

        $filters = $stored['filters'] ?? [];
        $mode = $stored['mode'] ?? 'all';

        if ($request->has('mode') && in_array($request->input('mode'), ['all', 'unique'], true)) {
            $mode = $request->input('mode');
            $stored['mode'] = $mode;
            $request->session()->put('participant_directory', $stored);
        }

        $filterRequest = Request::create('', 'GET', $filters);
        $baseQuery = $this->filteredParticipantQuery($filterRequest);

        $totalRegistrations = Participant::query()->count();
        $uniqueStudents = $this->uniqueCount(Participant::query());

        if ($mode === 'unique') {
            $results = $this->uniqueStudentsQuery($baseQuery)
                ->orderByDesc('last_registration')
                ->paginate(25)
                ->withQueryString();

            $filtered = $this->uniqueCount($baseQuery);
        } else {
            $results = $baseQuery
                ->with(['workshop', 'certificate'])
                ->orderByDesc('registered_at')
                ->paginate(25)
                ->withQueryString();

            $filtered = $results->total();
        }

        $filterOptions = [
            'workshops' => Workshop::query()
                ->orderByDesc('start_date')
                ->orderBy('title')
                ->get(['id', 'title', 'workshop_code']),
            'colleges' => $this->distinctValues('college_name'),
            'states' => $this->distinctValues('home_state'),
            'districts' => $this->distinctValues('home_district'),
            'courses' => $this->distinctValues('course'),
            'branches' => $this->distinctValues('branch'),
            'years' => $this->distinctValues('year'),
        ];

        $counts = [
            'total_registrations' => $totalRegistrations,
            'unique_students' => $uniqueStudents,
            'filtered' => $filtered,
        ];

        return view('admin.participants.index', compact(
            'results',
            'counts',
            'filterOptions',
            'mode',
            'filters'
        ));
    }

    public function export(Request $request)
    {
        $stored = $request->session()->get('participant_directory', []);
        $sessionFilters = $stored['filters'] ?? [];
        $filters = $request->boolean('replace_filters')
            ? $this->cleanFilters($request->only(self::FILTER_KEYS))
            : $this->cleanFilters(array_merge($sessionFilters, $request->only(self::FILTER_KEYS)));
        $mode = $request->input('export_mode', 'registrations');

        $filterRequest = Request::create('', 'GET', $filters);
        $query = $this->filteredParticipantQuery($filterRequest);

        if ($mode === 'unique') {
            return Excel::download(
                new UniqueStudentsExport($this->uniqueStudentsQuery($query)),
                'unique-students-' . now()->format('Y-m-d_H-i-s') . '.xlsx'
            );
        }

        return Excel::download(
            new AllRegistrationsExport($query),
            'participant-registrations-' . now()->format('Y-m-d_H-i-s') . '.xlsx'
        );
    }

    public function student(string $identifier)
    {
        $value = trim($identifier);
        $decoded = base64_decode($value, true);
        $value = $decoded !== false ? trim($decoded) : $value;

        $participant = Participant::query()
            ->whereRaw('LOWER(TRIM(email)) = ?', [Str::lower($value)])
            ->first();

        if (!$participant) {
            $mobile = preg_replace('/[^0-9]/', '', $value);
            if ($mobile !== '') {
                $participant = Participant::query()
                    ->whereRaw("REGEXP_REPLACE(TRIM(mobile), '[^0-9]', '') = ?", [$mobile])
                    ->first();
            }
        }

        if (!$participant && $value !== '') {
            $participant = Participant::query()
                ->whereRaw('LOWER(TRIM(enrollment_number)) = ?', [Str::lower($value)])
                ->first();
        }

        abort_unless($participant, 404);

        $identity = $this->identityForValue($participant);
        $registrations = Participant::query()
            ->whereRaw($this->identitySql() . ' = ?', [$identity])
            ->with(['workshop', 'certificate'])
            ->orderByDesc('registered_at')
            ->get();

        return view('admin.participants.student', [
            'profile' => $registrations->first(),
            'registrations' => $registrations,
        ]);
    }

    public function show(Participant $participant)
    {
        $participant->load(['workshop', 'certificate']);
        return view('admin.participants.show', compact('participant'));
    }

    private function filteredParticipantQuery(Request $request): Builder
    {
        $query = Participant::query();
        $search = trim((string) $request->input('search', ''));

        if ($search !== '') {
            $like = '%' . $search . '%';
            $query->where(function (Builder $q) use ($like) {
                $q->where('name', 'like', $like)
                    ->orWhere('email', 'like', $like)
                    ->orWhere('mobile', 'like', $like)
                    ->orWhere('college_name', 'like', $like)
                    ->orWhere('enrollment_number', 'like', $like)
                    ->orWhere('course', 'like', $like)
                    ->orWhere('branch', 'like', $like)
                    ->orWhere('year', 'like', $like)
                    ->orWhere('home_state', 'like', $like)
                    ->orWhere('home_district', 'like', $like)
                    ->orWhereHas('workshop', function (Builder $workshop) use ($like) {
                        $workshop->where('title', 'like', $like)
                            ->orWhere('workshop_code', 'like', $like);
                    });
            });
        }

        if ($request->filled('workshop_id')) {
            $query->where('workshop_id', (int) $request->input('workshop_id'));
        }

        if ($request->filled('workshop_status')) {
            $query->whereHas('workshop', fn (Builder $workshop) =>
                $workshop->where('status', $request->input('workshop_status'))
            );
        }

        $this->whereLike($query, 'college_name', $request->input('college_name'));
        $this->whereLike($query, 'course', $request->input('course'));
        $this->whereLike($query, 'branch', $request->input('branch'));
        $this->whereLike($query, 'year', $request->input('year'));
        $this->whereLike($query, 'home_state', $request->input('home_state'));
        $this->whereLike($query, 'home_district', $request->input('home_district'));
        $this->whereLike($query, 'enrollment_number', $request->input('enrollment_number'));

        if ($request->filled('registered_from')) {
            try {
                $query->where('registered_at', '>=', Carbon::parse($request->input('registered_from'))->startOfDay());
            } catch (\Throwable) {
            }
        }

        if ($request->filled('registered_to')) {
            try {
                $query->where('registered_at', '<=', Carbon::parse($request->input('registered_to'))->endOfDay());
            } catch (\Throwable) {
            }
        }

        return $query;
    }

    private function uniqueStudentsQuery(Builder $filteredQuery): Builder
    {
        $identitySql = $this->identitySql();
        $base = clone $filteredQuery;

        $latestIds = (clone $base)
            ->selectRaw('MAX(participants.id) as id')
            ->groupBy(DB::raw($identitySql));

        $aggregate = (clone $base)
            ->selectRaw("{$identitySql} as identity_key")
            ->selectRaw('COUNT(DISTINCT participants.workshop_id) as total_workshops')
            ->selectRaw('MIN(participants.registered_at) as first_registration')
            ->selectRaw('MAX(participants.registered_at) as last_registration')
            ->groupBy(DB::raw($identitySql));

        return Participant::query()
            ->whereIn('participants.id', $latestIds)
            ->leftJoinSub($aggregate, 'student_stats', function ($join) use ($identitySql) {
                $join->on(DB::raw($identitySql), '=', 'student_stats.identity_key');
            })
            ->select('participants.*')
            ->selectRaw('student_stats.total_workshops')
            ->selectRaw('student_stats.first_registration')
            ->selectRaw('student_stats.last_registration')
            ->with('workshop');
    }

    private function uniqueCount(Builder $filteredQuery): int
    {
        $identity = $this->identitySql();

        return (int) (clone $filteredQuery)
            ->selectRaw("COUNT(DISTINCT {$identity}) AS unique_count")
            ->value('unique_count');
    }

    private function identitySql(): string
    {
        return "COALESCE(
            NULLIF(LOWER(TRIM(participants.email)), ''),
            NULLIF(REGEXP_REPLACE(TRIM(participants.mobile), '[^0-9]', ''), ''),
            NULLIF(LOWER(TRIM(participants.enrollment_number)), ''),
            CONCAT(
                'fallback:',
                LOWER(TRIM(COALESCE(participants.name, ''))),
                '|',
                LOWER(TRIM(COALESCE(participants.college_name, '')))
            )
        )";
    }

    private function identityForValue(Participant $participant): string
    {
        if (trim((string) $participant->email) !== '') {
            return Str::lower(trim($participant->email));
        }

        $mobile = preg_replace('/[^0-9]/', '', (string) $participant->mobile);
        if ($mobile !== '') {
            return $mobile;
        }

        if (trim((string) $participant->enrollment_number) !== '') {
            return Str::lower(trim($participant->enrollment_number));
        }

        return 'fallback:' . Str::lower(trim((string) $participant->name))
            . '|' . Str::lower(trim((string) $participant->college_name));
    }

    private function distinctValues(string $column)
    {
        return Participant::query()
            ->whereNotNull($column)
            ->whereRaw("TRIM({$column}) <> ''")
            ->select($column)
            ->distinct()
            ->orderBy($column)
            ->pluck($column);
    }

    private function whereLike(Builder $query, string $column, mixed $value): void
    {
        $value = trim((string) $value);
        if ($value !== '') {
            $query->where($column, 'like', '%' . $value . '%');
        }
    }

    private function cleanFilters(array $filters): array
    {
        $clean = [];
        foreach (self::FILTER_KEYS as $key) {
            $value = $filters[$key] ?? null;
            if ($value !== null && $value !== '') {
                $clean[$key] = is_string($value) ? trim($value) : $value;
            }
        }
        return $clean;
    }
}
