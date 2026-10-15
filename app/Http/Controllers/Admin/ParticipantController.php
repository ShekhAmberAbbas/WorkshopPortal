<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ParticipantsExport;
use App\Http\Controllers\Controller;
use App\Models\CertificateTemplate;
use App\Models\Participant;
use App\Models\Workshop;
use App\Services\CertificateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use RuntimeException;

class ParticipantController extends Controller
{
    public function __construct(protected CertificateService $certificateService)
    {
    }

    public function index(Request $request, Workshop $workshop)
    {
        $query = $workshop->participants()
            ->with(['certificate'])
;

        if ($search = $request->string('search')->trim()->value()) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%")
                  ->orWhere('whatsapp_number', 'like', "%{$search}%")
                  ->orWhere('enrollment_number', 'like', "%{$search}%")
                  ->orWhere('home_district', 'like', "%{$search}%");
            });
        }

        $participants = $query->orderBy('registered_at', 'desc')->paginate(20)->withQueryString();

        return view('admin.workshops.participants.index', compact('workshop', 'participants'));
    }

    public function destroy(Workshop $workshop, Participant $participant)
    {
        abort_unless($participant->workshop_id === $workshop->id, 404);

        $participant->delete();

        return back()->with('status', 'Participant removed.');
    }

    public function generateCertificate(Workshop $workshop, Participant $participant)
    {
        abort_unless($participant->workshop_id === $workshop->id, 404);

        // Template comes from the WORKSHOP, never from the request — a
        // certificate_template_id posted from the browser could otherwise
        // let someone point generation at a template that isn't this
        // workshop's own, which is exactly what requirement #29 rules out.
        $template = $workshop->certificateTemplate;

        if (! $template || ! $template->status) {
            return back()->with('status', 'This workshop has no active certificate template assigned — set one on the workshop\'s Edit page first.');
        }

        try {
            $this->certificateService->generate($participant, $template);
        } catch (RuntimeException $e) {
            return back()->with('status', $e->getMessage());
        }

        return back()->with('status', "Certificate generated for {$participant->name}.");
    }

    public function downloadCertificate(Workshop $workshop, Participant $participant)
    {
        abort_unless($participant->workshop_id === $workshop->id, 404);

        $certificate = $participant->certificate;
        abort_if(! $certificate || $certificate->status !== 'generated', 404);
        abort_unless(
            $workshop->certificatesAvailableForDownload(),
            403,
            'Certificate downloads are available starting on the workshop date.'
        );

        return Storage::disk('local')->download($certificate->file_path, $certificate->downloadFilename());
    }

    public function export(Workshop $workshop)
    {
        return Excel::download(new ParticipantsExport($workshop), "participants-{$workshop->workshop_code}.xlsx");
    }
}
