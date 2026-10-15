<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWorkshopRequest;
use App\Http\Requests\UpdateWorkshopRequest;
use App\Models\CertificateTemplate;
use App\Models\Workshop;
use App\Services\QrCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class WorkshopController extends Controller
{
    public function __construct(protected QrCodeService $qrCodeService)
    {
    }

    public function index(Request $request)
    {
        $query = Workshop::withCount(['participants', 'certificates']);

        if ($search = $request->string('search')->trim()->value()) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('college_name', 'like', "%{$search}%")
                  ->orWhere('workshop_code', 'like', "%{$search}%");
            });
        }

        if ($status = $request->string('status')->value()) {
            $query->where('status', $status);
        }

        $sort = in_array($request->get('sort'), ['start_date', 'title', 'created_at'])
            ? $request->get('sort')
            : 'created_at';
        $direction = $request->get('direction') === 'asc' ? 'asc' : 'desc';

        $workshops = $query->orderBy($sort, $direction)
            ->paginate(15)
            ->withQueryString();

        return view('admin.workshops.index', compact('workshops'));
    }

    public function create()
    {
        $templates = CertificateTemplate::where('status', true)->orderBy('name')->get();

        return view('admin.workshops.create', compact('templates'));
    }

    public function store(StoreWorkshopRequest $request)
    {
        $data = $request->validated();
        $data['duration_days'] = Carbon::parse($data['start_date'])->diffInDays(Carbon::parse($data['end_date'])) + 1;
        $data['certificate_enabled'] = true;
        $data['auto_generate_certificate'] = true;
        $workshop = Workshop::create([
            ...$data,
            'created_by' => $request->user()->id,
        ]);

        $this->qrCodeService->generateForWorkshop($workshop);

        return redirect()
            ->route('admin.workshops.show', $workshop)
            ->with('status', 'Workshop created. Registration link and QR are ready below.');
    }

    public function show(Workshop $workshop)
    {
        $workshop->loadCount(['participants', 'certificates'])->load('qrCode');

        return view('admin.workshops.show', compact('workshop'));
    }

    public function edit(Workshop $workshop)
    {
        $templates = CertificateTemplate::where('status', true)->orderBy('name')->get();

        return view('admin.workshops.edit', compact('workshop', 'templates'));
    }

    public function update(UpdateWorkshopRequest $request, Workshop $workshop)
    {
        $data = $request->validated();
        $data['duration_days'] = Carbon::parse($data['start_date'])->diffInDays(Carbon::parse($data['end_date'])) + 1;
        $data['certificate_enabled'] = true;
        $data['auto_generate_certificate'] = true;
        $workshop->update($data);

        return redirect()
            ->route('admin.workshops.show', $workshop)
            ->with('status', 'Workshop updated.');
    }

    public function destroy(Workshop $workshop)
    {
        $workshop->delete(); // soft delete — participants/certificates history is preserved

        return redirect()
            ->route('admin.workshops.index')
            ->with('status', 'Workshop deleted.');
    }

    public function toggleStatus(Request $request, Workshop $workshop)
    {
        $status = $request->validate([
            'status' => ['required', 'in:draft,active,inactive,completed'],
        ])['status'];

        $workshop->update(['status' => $status]);

        return back()->with('status', "Workshop status updated to {$status}.");
    }

    /**
     * Serve the saved SVG directly from the public filesystem disk.
     * This deliberately avoids Storage::url() and the public/storage symlink,
     * so the QR preview and print page work even when that symlink is absent.
     */
    public function qrImage(Workshop $workshop)
    {
        $workshop->loadMissing('qrCode');
        $expectedPath = 'qrcodes/' . $workshop->workshop_code . '.svg';
        $qrCode = $workshop->qrCode;

        // Repair a missing/stale QR file and database path automatically.
        if (! $qrCode || $qrCode->file_path !== $expectedPath || ! Storage::disk('public')->exists($expectedPath)) {
            $qrCode = $this->qrCodeService->generateForWorkshop($workshop);
        }

        $svg = Storage::disk('public')->get($expectedPath);

        // If a file was replaced/corrupted, regenerate it once before serving.
        if (! is_string($svg) || ! str_contains(strtolower($svg), '<svg')) {
            $qrCode = $this->qrCodeService->generateForWorkshop($workshop);
            $svg = Storage::disk('public')->get($expectedPath);
        }

        abort_unless(is_string($svg) && str_contains(strtolower($svg), '<svg'), 404, 'QR code could not be generated.');

        return response($svg, 200, [
            'Content-Type' => 'image/svg+xml; charset=UTF-8',
            'Content-Disposition' => 'inline; filename="' . $workshop->workshop_code . '.svg"',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, no-store, no-cache, must-revalidate',
        ]);
    }

    /** Download QR codes in their actual stored format (SVG). */
    public function downloadQr(Request $request, Workshop $workshop)
    {
        return response($this->qrCodeService->svgFor($workshop), 200, [
            'Content-Type' => 'image/svg+xml; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $workshop->workshop_code . '.svg"',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function printQr(Workshop $workshop)
    {
        $workshop->loadMissing('qrCode');

        return view('admin.workshops.print-qr', compact('workshop'));
    }
}
