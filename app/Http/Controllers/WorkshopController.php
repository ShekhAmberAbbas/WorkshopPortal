<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWorkshopRequest;
use App\Http\Requests\UpdateWorkshopRequest;
use App\Models\CertificateTemplate;
use App\Models\Workshop;
use App\Services\QrCodeService;
use Illuminate\Http\Request;
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
        $workshop = Workshop::create([
            ...$request->validated(),
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
        $workshop->update($request->validated());

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

    public function toggleStatus(Workshop $workshop)
    {
        $workshop->update([
            'status' => $workshop->status === 'active' ? 'inactive' : 'active',
        ]);

        return back()->with('status', "Workshop is now {$workshop->status}.");
    }

    public function downloadQr(Request $request, Workshop $workshop)
    {
        if ($request->get('format') === 'svg') {
            return response($this->qrCodeService->svgFor($workshop), 200, [
                'Content-Type' => 'image/svg+xml',
                'Content-Disposition' => "attachment; filename={$workshop->workshop_code}.svg",
            ]);
        }

        $workshop->loadMissing('qrCode');

        if (! $workshop->qrCode) {
            $this->qrCodeService->generateForWorkshop($workshop);
            $workshop->refresh()->load('qrCode');
        }

        return Storage::disk('public')->download(
            $workshop->qrCode->file_path,
            "{$workshop->workshop_code}.png"
        );
    }

    public function printQr(Workshop $workshop)
    {
        $workshop->loadMissing('qrCode');

        return view('admin.workshops.print-qr', compact('workshop'));
    }
}
