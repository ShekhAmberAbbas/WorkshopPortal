<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CertificateExport;
use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Workshop;
use App\Services\CertificateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use RuntimeException;

class CertificateController extends Controller
{
    public function __construct(protected CertificateService $certificateService)
    {
    }

    public function index(Workshop $workshop)
    {
        $certificates = $workshop->certificates()
            ->with('participant')
            ->latest()
            ->paginate(20);

        $ungeneratedCount = $workshop->participants()->whereDoesntHave('certificate')->count();

        return view('admin.certificates.index', compact('workshop', 'certificates', 'ungeneratedCount'));
    }

    /**
     * Resolves and validates the ONE template this workshop is allowed to
     * use. Centralized here so bulkGenerate/bulkGenerateSelected can't
     * drift from each other or from generateCertificate() in
     * ParticipantController.
     */
    protected function requireWorkshopTemplate(Workshop $workshop)
    {
        $template = $workshop->certificateTemplate;

        abort_if(! $template || ! $template->status, 422,
            "This workshop has no active certificate template assigned — set one on the workshop's Edit page first.");

        return $template;
    }

    public function bulkGenerate(Workshop $workshop)
    {
        $template = $this->requireWorkshopTemplate($workshop);

        $participants = $workshop->participants()->whereDoesntHave('certificate')->get();

        $generated = 0;
        $failed = 0;

        foreach ($participants as $participant) {
            try {
                $this->certificateService->generate($participant, $template);
                $generated++;
            } catch (RuntimeException $e) {
                $failed++;
            }
        }

        return back()->with('status', "Generated {$generated} certificates." . ($failed ? " {$failed} skipped." : ''));
    }

    /**
     * Same as bulkGenerate(), but scoped to an explicit, admin-picked list of
     * participant IDs — the "filter first, then generate only these" flow.
     */
    public function bulkGenerateSelected(Request $request, Workshop $workshop)
    {
        $request->validate([
            'participant_ids' => 'required|array|min:1',
            'participant_ids.*' => 'integer',
        ]);

        $template = $this->requireWorkshopTemplate($workshop);

        // Re-scope to this workshop explicitly — participant_ids came from the
        // client, so we never trust it blindly even though the UI only ever
        // renders checkboxes for this workshop's own participants.
        $participants = $workshop->participants()
            ->whereIn('id', $request->input('participant_ids'))
            ->whereDoesntHave('certificate')
            ->get();

        $generated = 0;
        $failed = 0;

        foreach ($participants as $participant) {
            try {
                $this->certificateService->generate($participant, $template);
                $generated++;
            } catch (RuntimeException $e) {
                $failed++;
            }
        }

        $skippedAlready = count($request->input('participant_ids')) - $participants->count();

        $message = "Generated {$generated} certificates.";
        if ($failed) $message .= " {$failed} failed.";
        if ($skippedAlready) $message .= " {$skippedAlready} already had one.";

        return back()->with('status', $message);
    }

    public function revoke(Certificate $certificate)
    {
        $certificate->update(['status' => 'revoked']);

        return back()->with('status', "Certificate {$certificate->certificate_id} revoked.");
    }

    public function reactivate(Certificate $certificate)
    {
        abort_unless(Storage::disk('local')->exists($certificate->file_path), 404, 'Original certificate file is missing — regenerate instead.');

        $certificate->update(['status' => 'generated']);

        return back()->with('status', "Certificate {$certificate->certificate_id} reactivated.");
    }

    public function download(Certificate $certificate)
    {
        abort_unless($certificate->status === 'generated', 404, 'This certificate is unavailable for download.');
        abort_unless(
            $certificate->workshop?->certificatesAvailableForDownload(),
            403,
            'Certificate downloads are available starting on the workshop date.'
        );

        return Storage::disk('local')->download($certificate->file_path, $certificate->downloadFilename());
    }

    public function export(Workshop $workshop)
    {
        return Excel::download(new CertificateExport($workshop), "certificates-{$workshop->workshop_code}.xlsx");
    }
}
